<?php
    $aColumns = array( 'file', 'name', 'from_ckeditor' );
    
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "id";
    
    /* DB table to use */
    $table = $db->page;
    $where = array(
            '$or' => array(
            array("from_ckeditor" => array('$exists' => true)),
                    array("inner" => array('$exists' => true))));
    if(is_array($_GET['sSearch']))
        return;
    /*if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $where['$and'][1] = array('$or' => array());
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
            {
                $where['$and'][1]['$or'][$aColumns[$i]] = array('$regex' => $_GET['sSearch'] );
            }
        }
    }*/

    if(sizeof($where))
        $result = $table->find($where);
    else
        $result = $table->find();
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $order = array();
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                if($aColumns[ intval( $_GET['iSortCol_'.$i] ) ] == "name")
                    $order['name_'.$config['language']] = strtolower($_GET['sSortDir_'.$i]) == "asc" ? 1 : -1;
                else if(intval( $_GET['iSortCol_'.$i] ) < sizeof($aColumns))
                    $order[$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]] = 
                strtolower($_GET['sSortDir_'.$i]) == "asc" ? 1 : -1;
            }
        }
        $result = $result->sort($order);
    }
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $result = $result->skip(intval( $_GET['iDisplayStart'] ))->limit(intval( $_GET['iDisplayLength'] ));
    }
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $table->find(array(
            '$or' => array(
            array("from_ckeditor" => array('$exists' => true)),
                    array("inner" => array('$exists' => true)))))->count(),
        "iTotalDisplayRecords" => $result->count(),
        "aaData" => array()
    );
    foreach($result as $row)
    {
        $row[0] = isset($row['from_ckeditor']) ? Page::getStruct($row['_id']) : $row['page'];
        $row[1] = $row['name_'.$config['language']];
        if(isset($row['from_ckeditor']))
        {
            $row[2] = $lang['Yes'];
            $ref = $row['from_ckeditor'];
        }
        else
        {
            $row[2] = $lang['No'];
            $ref = $row['page'];
        }
        $row[3] = str_replace_first('$', $lang['edit'], str_replace_first('$', $ref, file_get_contents("./template/".$config['template'].'/page_edit_style.php')));
        if(isset($row['from_ckeditor']))
            $row[4] = str_replace_first('$', $lang['delete'], str_replace_first('$', $ref, file_get_contents("./template/".$config['template'].'/page_delete_style.php')));
        else
            $row[4] = "";
        $output['aaData'][] = $row;
    }
    
    echo json_encode( $output );
?>