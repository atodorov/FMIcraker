</div>
<?php if($page_editable && $user->hasPriv("pages")){ ?>
<script>
        CKEDITOR.disableAutoInline = true;

        var editor = CKEDITOR.inline( 'body', {
    on: {
        blur: function( event ) {
            var data = event.editor.getData();
            $.post("ckeditor_changes", "data="+data+"&id=<?=$page_id?>");
        }
    }
} );
</script>
<?php } ?>
</body>
</html>