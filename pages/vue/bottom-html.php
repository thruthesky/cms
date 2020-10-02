
<!-- The Modal -->
<div id="myModal" class="modal" :style="{display: dialog.display}" @click="dialog.display='none'">

	<!-- Modal content -->
	<div id="modal-content" class="modal-content" onclick="preventBubbling()">
		<div class="modal-header flex align-items-center justify-content-between bg-lighter" v-if="dialog.header">
			<div class="py-2 px-3 fs-lg">{{ dialog.header }}</div>
            <span class="close fs-lg py-2 px-3 pointer" @click="dialog.display='none'">&times;</span>
		</div>
		<div class="modal-body p-3 bg-white">
            <div v-html="dialog.body"></div>
		</div>
		<div class="modal-footer py-2 px-3 bg-lighter" v-if="dialog.footer">
			<div class="fs-md">{{ dialog.footer }}</div>
		</div>
	</div>

</div>

<script>
	function preventBubbling(event) {
	    window.event.cancelBubble=true;
    }
</script>