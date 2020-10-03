<div class="dialog-modal" :style="{display: dialog.display}" @click="dialog.display='none'">
    <div class="dialog-modal-content" onclick="preventBubbling()">
        <div class="dialog-modal-header flex align-items-center justify-content-between bg-lighter"
             v-if="dialog.header">
            <div class="py-2 px-3 fs-lg">{{ dialog.header }}</div>
            <span class="close fs-lg py-2 px-3 pointer" @click="dialog.display='none'">&times;</span>
        </div>
        <div class="dialog-modal-body p-3 bg-white">
            <div v-html="dialog.body"></div>
        </div>
        <div class="dialog-modal-footer py-2 px-3 bg-lighter" v-if="dialog.footer">
            <div class="fs-md">{{ dialog.footer }}</div>
        </div>
    </div>
</div>
<div class="toast-modal pointer" :style="{display: toastOptions.display}">
    <div class="toast-modal-content d-flex justify-content-between bg-light" :class="toastOptions.cssClass">
        <div class="toast-modal-body flex-grow-1 py-2 px-3" v-html="toastOptions.body"></div>
        <div class="py-2 px-3 pointer" @click="toastOptions.display='none'">&times;</div>
    </div>
</div>
<script>
    function preventBubbling(event) {
        window.event.cancelBubble = true;
    }
</script>
<?php if ( in('acode') ) { ?>
<script>
    $$(function () {
        /// alert code
        setTimeout(function(){
            vm.toastOk({body: '<?=tr(in('acode'))?>'});
        }, 100);
    });
</script>
<?php } ?>