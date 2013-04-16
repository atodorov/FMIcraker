<a href="/admin-panel/pages/add"><?=$lang['add']?></a><br>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="pages">
    <thead>
        <tr>
            <th><?=$lang['file']?></th>
            <th><?=$lang['name']?></th>
            <th><?=$lang['inner']?></th> 
            <th><?=$lang['edit']?></th>
            <th><?=$lang['delete']?></th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
    <tfoot>
        <tr>
            <th><?=$lang['file']?></th>
            <th><?=$lang['name']?></th>
            <th><?=$lang['inner']?></th> 
            <th><?=$lang['edit']?></th>
            <th><?=$lang['delete']?></th>
        </tr>
    </tfoot>
</table>
<script type="text/javascript" charset="utf-8">
$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    if ( typeof sNewSource != 'undefined' && sNewSource != null )
    {
        oSettings.sAjaxSource = sNewSource;
    }
    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];

    this.oApi._fnServerParams( oSettings, aData );

    oSettings.fnServerData( oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );

        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }

        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
        that.fnDraw();

        if ( typeof bStandingRedraw != 'undefined' && bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.fnDraw( false );
        }

        that.oApi._fnProcessingDisplay( oSettings, false );

        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback != null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
}

            $(document).ready(function() {
                var oTable = $('#pages').dataTable( {
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "/pages_q",
                 "aoColumns": [
                   { "bSortable": true },
                   { "bSortable": true }, 
                   { "bSortable": true },
                   { "bSortable": false },
                   { "bSortable": false }] 
                } );
            } );
            $("[data-del-id]").live("click", function(){
                if(confirm("<?=$lang['delete_page_confirm']?>"))
                {
                    $.post("", {"del_id" : $(this).attr("data-del-id")});
                    oTable = $('#pages').dataTable()._fnAjaxUpdate();
                }
            })
</script>