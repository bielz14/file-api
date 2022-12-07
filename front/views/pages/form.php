<div class="form-upload">
	<div id="form-section">
		<form action="/" method="post" enctype="multipart/form-data">
			<div class="form-input-section">
				<label for="file">Choose file</label>
				<input type="file" name="file" style="display: none">
				<button id="choose-file-btn" class="btn">Browse</button>
			</div>
			<span class="form-notice">We support .pdf,.jpeg file formats and size up to 5MB.</span>
			<div class="form-submit-section">
				<button id="upload-file-btn" class="btn" type="submit">Submit</button>
			</div>
		</form>
	</div>
	<div id="success-uploading-section">
		<div class="success-uploading-msg-section">
			<span id="upload-msg">
				<b>File was uploaded successfully</b>
			</span>
			<br>
			<br>
			<span id="file-hash"></span>
		</div>
		<div class="form-close-section">
			<button id="close-file-btn" class="btn">Close</button>
		</div>
	</div>
</div>