
<?php

	$chkInf = $db->query("SELECT tc.*, f.code, t.name as tName, c.nombre as cName, p.name as pName
		FROM TargetsChecklist tc 
		LEFT JOIN Checklist c ON c.id = tc.checklistId
		LEFT JOIN Targets t ON t.id = tc.targetsId
		LEFT JOIN Projects p ON p.id = t.projectsId
		LEFT JOIN Frequencies f ON f.id = tc.frequency
		WHERE tc.Id = $_GET[trgtChk]
	")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($chkInf);

?>

<h3></h3>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link " id="home-tab" data-toggle="tab" 
    	href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo TR('surveyAnalysis'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" id="profile-tab" data-toggle="tab" 
    	href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo TR('socialMon'); ?></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade " id="home" role="tabpanel" aria-labelledby="home-tab">
  	<div style="margin-top: 10px;">			  		
	  	<h3>
	  		<?php echo "[$chkInf[pName]] $chkInf[tName] - $chkInf[cName] (".TR($chkInf['code']).")" ?><br/>
	  		<?php echo TR('survey'); ?>
  		</h3>
	  	<?php include raiz().'analysis/checklist/index.php'; ?>
  	</div>
  </div>
  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  	<div style="margin-top: 10px;">			  		
	  	<h3>
	  		<?php echo "[$chkInf[pName]] $chkInf[tName] - $chkInf[cName] (".TR($chkInf['code']).") " ?><br/>
	  		<?php echo TR('socialMon'); ?>
	  	</h3>
	  	<?php include raiz().'analysis/socialMon/index.php'; ?>
	</div>
  </div>
</div>
