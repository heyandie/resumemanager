<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style type="text/css">

body{
	/*background-image: url(images/paper.jpg);
	background-size: auto 100%;
	background-position: center;*/
	min-height: 1100px;
	color: #080808;
	font-size: 12.5px;
	font-family: Helvetica,sans-serif;
}
@page { margin: 0.5in 0.6in;
		box-decoration-break: slice; 
	}
body { margin: 0px; }
#resume-preview{
	
}
body,div,span,b,h1,h2,h3,h4,h5{
	color: #080808;
}

span.fullname{
	margin: 0px;
	font-size: 27px;
	color: #000;
	font-weight: bold;
}
.subname{
  font-weight: bold;
  color: #a37720;
  }
span.lastname{
	color: #D9AF0B;
}
div.heading{
	font-size: 15px;
	font-weight: bold;
	border-bottom: 1px dotted #111;
	color: #080808;
}
.label-col{
  color: #D9AF0B;
  font-weight: bold;
  width: 120px !important;
  text-align: left;
  font-size: 14px;
  padding-right: 15px;
}
.schoolname{
	font-weight: bold;
	display: block;
}
table{
	width: 100%;
	border-spacing: 0px;
	margin-top: 8px;
}
table tr{
	padding: 0px;
}
tr.affiliations td{
	padding-bottom:3px;
}
table td{
	padding-bottom: 7px;
	padding-left: 0px;
	vertical-align: top;
}
td.right-column{
	padding-left: 18px;
	width: 165px;
	text-align: right;
	color: #444 !important;
}
.work-description-text{
  list-style-type: disc;
  margin-left: 12px;
  display:list-item;
}
.divider{
	border-bottom: 1px dotted #222;
	margin: 8px 0px;
}

</style>
</head>
<body>
<div id="resume-manager" class="grid-container">
<div id="resume-preview" 
	class="grid-100 grid-parent tablet-grid-100 mobile-grid-100">

	<div id="resume-page-1">
		<div class="grid-container">
			<div class="personal-info-preview">
				<?php if(isset($colorscheme)):?>
				<style type="text/css">
				<?php if($colorscheme=="plum"):?>
				.label-col{
				  color: #AC2B58 !important;
				}
				.subname{
				  color: #6d263f !important;
				}
				<?php elseif($colorscheme=="orange"):?>
				.label-col{
				  color: #db6408 !important;
				}
				.subname{
				  color: #774303 !important;
				}
				<?php elseif($colorscheme=="blue"):?>
				.label-col{
				  color: #1f7287 !important;
				}
				.subname{
				  color: #2e5e5d !important;
				}
				<?php endif ?>
				</style>
				<?php endif ?>
				<?php $personal_info=json_decode($personal_info);?>
				<div><span class="fullname"><?php echo $personal_info->first_name;?></span>
					<span class="fullname"><?php echo $personal_info->last_name;?></span></div>
				<div><span><?php echo $personal_info->address;?></span></div>
				<div><b>Email:</b> <?php echo $personal_info->email_address;?> /
				 <b>Mobile:</b> <?php echo $personal_info->mobile_number;?></div>
				
			</div>
			<div class="divider"></div>
				
			<?php if($student_type=="Graduate"):?>
			<table class="resume-table">
				<?php $education_graduate=json_decode($education_graduate);?>
				<tr>
					<td class="label-col">EDUCATION</td>
					<td>
						<span class="schoolname"><?php echo $education_graduate->school_name; ?></span></br>
						<span class="subname"><?php echo $education_graduate->degree_program; ?></span></br>

						<?php $graduate_recognitions=json_decode($graduate_recognitions);?>

						<?php foreach($graduate_recognitions->recognitions as $item):?>
						<div><i class="work-description-text"><?php echo $item->award;?></i></br></div>
					
						<?php endforeach ?>
					</td>
					<td class="right-column">	
						
						<?php echo $education_graduate->graduation_month;?>&nbsp;
						<?php echo $education_graduate->graduation_year;?>
		
					</td>
				</tr>
				<?php endif ?>
				<?php $education_undergraduate=json_decode($education_undergraduate);?>
			</table>
			<table>
				<tr>
					<td class="label-col"><?php if($student_type=='"Undergraduate"'):?>EDUCATION<?php endif;?></td>
					<td>
						<span class="schoolname"><?php echo $education_undergraduate->school_name; ?></span></br>
						<span class="subname"><?php echo $education_undergraduate->degree_program; ?></span></br>

						<?php $undergraduate_recognitions=json_decode($undergraduate_recognitions);?>

						<?php foreach($undergraduate_recognitions->recognitions as $item):?>
						<div><i class="work-description-text"><?php echo $item->award;?></i></br></div>
					
						<?php endforeach ?>
					</td>
					<td class="right-column">	
						
						<?php echo $education_undergraduate->graduation_month;?>&nbsp;
						<?php echo $education_undergraduate->graduation_year;?>
		
					</td>
				</tr>
			</table>
			<table>
				<?php $education_highschool=json_decode($education_highschool);?>
				<tr>
					
					<td class="label-col"> </td>
					<td>
						<span class="schoolname"><?php echo $education_highschool->school_name; ?></span></br>
						<span class="subname">High School Diploma</span></br>

						<?php $highschool_recognitions=json_decode($highschool_recognitions);?>

						<?php foreach($highschool_recognitions->recognitions as $item):?>
						<div><i class="work-description-text"><?php echo $item->award;?></i></div>
					
						<?php endforeach ?>
					</td>
					<td class="right-column">
						<?php echo $education_highschool->graduation_month;?>&nbsp;
						<?php echo $education_highschool->graduation_year;?>
						
					</td>
				</tr>
			</table>
		

			<?php $work_experience=json_decode($work_experience);?>
			<?php if(!empty($work_experience)):?>
			<div class="divider">
				
			</div>
			<?php endif?>


			<?php $count=0;?>
			<?php foreach($work_experience as $item):?>
			<table class="resume-table">
				<tr>
					<td class="label-col"><?php if($count==0){echo "WORK EXPERIENCE";}else echo " ";?></td>
					<td>
						<div><b><?php echo $item->company_name;?></b></div>
						<div class="subname"><?php echo $item->position;?></div>
						<?php foreach($item->work_descriptions as $description):?>
						<div><i class="work-description-text"><?php echo $description->work_description;?></i></div>
						<?php endforeach?>
				
					</td>
					<td class="right-column">
						<div><?php echo $item->from_month." ".$item->from_year." - ".$item->to_month." ".$item->to_year;?></div>
						<div><i><?php echo $item->company_location;?></i></div>
					</td>
				</tr>
			</table>
			<?php $count=$count+1;?>
			<?php endforeach?>

			<?php $affiliations=json_decode($affiliations);?>
			<?php if(!empty($affiliations)):?>
			<div class="divider">
				
			</div>
			<?php endif?>

			<?php $count=0;?>
			<?php foreach($affiliations as $item):?>
			
			
				<?php $i=0;?>

				<?php foreach($item->committees as $committee):?>
				<div style="page-break-inside: avoid;">
				<table class="resume-table">
					
					<tr class="affiliations">
						
						<td class="label-col"><?php if($count==0&&$i==0){echo "ACTIVITIES";}else echo " ";?></td>
						<td><div><b><?php if($i==0){echo $item->affiliation_name;}?></b></div></td>
						<td></td>
					</tr>
					<tr>
						<td class="label-col"></td>
						<td>
							
							<div class="subname"><?php echo $committee->committee_name;?></div>
							<div><i class="work-description-text"><?php echo $committee->task_description;?></i></div>
					
						</td>
						<td class="right-column">
							<div><?php echo $committee->from_month." ".$committee->from_year;?>
								<?php if($committee->to_present){
									echo " - Present";
								}else{
									if($committee->to_month&&$committee->to_year!='0000')
										echo " - ".$committee->to_month." ".$committee->to_year;
								}
								?>
							</div>
						</td>
					</tr>
				</table>
				</div>
				<?php $i=$i+1;?>
				<?php endforeach?>
			
			<?php $count=$count+1;?>
			<?php endforeach?>
			
			<?php if($skills!=""):?>
			<div class="divider">
				
			</div>
			<div class="grid-100 grid-parent resume-row">

			
				<table class="resume-table">
					<tr>
						<td class="label-col"><?php echo "SKILLS";?></td>
						<td>
							
							
							<div><span><?php echo $skills;?></span></div>
							
					
						</td>
					</tr>
				</table>
			</div>
			<?php endif?>
		</div>
	</div>
</div>
</div>
</body>
</html>