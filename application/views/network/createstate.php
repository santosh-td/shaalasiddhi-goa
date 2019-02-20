<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
				<?php if($isPop==0){?>
				<a href="<?php
						$args=array("controller"=>"network","action"=>"network");						
						echo createUrl($args); 
						?>">
						<i class="fa fa-chevron-circle-left vtip" title="Back"></i>
					Manage Levels
					</a> &rarr;				
				<?php } ?>				
					Add State
				</h1>
				<div class="clr"></div>
				<div class="">
					<div class="ylwRibbonHldr">
						<div class="tabitemsHldr"></div>
					</div>
					<div class="subTabWorkspace pad26">
						<div class="form-stmnt">
							<form method="post" id="create_state_form" action="">
											
								<div class="boxBody">
									<dl class="fldList">
										<dt>State Name<span class="astric">*</span>:</dt>
                                                                                <dd class="the-basics state"><div class="row"><div class="col-sm-9"><input type="text" value="" class="form-control typeahead tt-query" name="name" required maxlength="100"/></div></div></dd>
									</dl> 
									<dl class="fldList">
										<dt></dt>
										<dd class="nobg">
											<div class="row">
												<div class="col-sm-6">
													<br>
													<input type="submit" value="Add State" class="btn btn-primary">
												</div>
											</div>
										</dd>
									</dl>
								</div>
								<div class="ajaxMsg"></div>
								<input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
							</form>
						</div>
					</div>
				</div>
<script>
$(document).ready(function(){
	states=[];
	<?php foreach($states as $state){ ?>
			states.push('<?php echo $state['state_name']; ?>');
		<?php } ?>			  
	  $('.the-basics.state .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 3		
      },
      {
        name: 'states',
        source: substringMatcher(states),
		limit:30
      });
	  $('.state .typeahead').bind('typeahead:select', function(ev, suggestion) {	
		$('.state .typeahead').typeahead('val','');
		 alert("This State already exists! Please type a new state name.");		
		 
	  }); 	 
}); 		
</script>