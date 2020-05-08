
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
	$target = $db->query("SELECT t.*, tc.frequency, p.description
		FROM TargetsChecklist tc 
		LEFT JOIN Targets t ON t.id = tc.targetsId
		LEFT JOIN Projects p ON p.id = t.projectsId
		WHERE tc.id = $_GET[trgtChk]
	")->fetchAll(PDO::FETCH_ASSOC)[0];


	$dims = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $target[id] AND type = 'structure' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

?>


<h3></h3>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" 
    	href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo TR('surveyAnalysis'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link " id="profile-tab" data-toggle="tab" 
    	href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo TR('socialMon'); ?></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  	<div style="margin-top: 10px;">			  		
	  	<span style="font-size:1.2em;">
	  		<?php echo "<strong>".TR('project')."</strong>: $chkInf[pName]"; ?><br/>
	  		<?php echo "<strong>".TR('target')."</strong>: $chkInf[tName]"; ?><br/>
	  		<?php echo "<strong>".TR('survey')."</strong>: $chkInf[cName]"; ?><br/>
	  		<?php echo "<strong>".TR('frequency')."</strong>: ".TR($chkInf['code']); ?><br/>
  		</span>
	  	<?php include raiz().'analysis/checklist/index.php'; ?>
  	</div>
  </div>
  <div class="tab-pane fade " id="profile" role="tabpanel" aria-labelledby="profile-tab">
  	<div style="margin-top: 10px;">			  		
	  	<span style="font-size:1.2em;">
	  		<?php echo "<strong>".TR('project')."</strong>: $chkInf[pName]"; ?><br/>
	  		<?php echo "<strong>".TR('target')."</strong>: $chkInf[tName]"; ?><br/>
	  		<?php echo "<strong>".TR('survey')."</strong>: $chkInf[cName]"; ?><br/>
	  		<?php echo "<strong>".TR('frequency')."</strong>: ".TR($chkInf['code']); ?><br/>
	  		
	  	</span>
	  	<?php include raiz().'analysis/socialMon/index.php'; ?>
	</div>
  </div>
</div>
