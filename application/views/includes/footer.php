<script type="text/javascript" src="<?php echo base_url();?>js/knockout-3.1.0.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/knockout.mapping-latest.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/iscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/knockout-sortable.min.js"></script>
<script type="text/javascript">
    
</script>

<?php if(isset($resume_editor)):?>
<script type="text/javascript" src="<?php echo base_url();?>js/resume-1.0.5.min.js"></script>
<script type="text/javascript">
	$(function(){
		var resumeManagerModel = new ResumeManagerModel();
		ko.applyBindings(resumeManagerModel, document.getElementById('resume-manager'));
		$(window).on('beforeunload', function(){
                  $('body').append('<div class="div-white"/>');
           });
		$("body").on('click','#submit-error .btn-dismiss',function(){
						$(".div-mask").fadeOut('fast');
					});
		$("body").on('click','#submit-success .btn-dismiss',function(){
						$(".div-mask").fadeOut('fast');
						resumeManagerModel.showPersonalInfo();
					});
	});

</script>
<?php endif?>
</body>


</html>