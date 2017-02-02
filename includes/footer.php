<style type="text/css">
	.div
	{
		display: inline-block;
		margin: 3%;
		width: 25%; 
		
	}
	.Copyright
	{
		background-color: #2480A0;
	}
	.footer
	{
		background-color: #2B8BAD;
		color: white;
		width: 90%;
		text-align: center;
		position: center;
	}
</style>
<?php 
$nombreTotalConnecté=2;
$nombreMembreConnecté=1;
$nombreInvité=1;
$nomDuSite="Site du savoir";


?>

	<div class="footer">
		<div class="div">
		<h3>Qui est en ligne? </h3>
		<?php 

		 
			
			echo "<p>Il y a actuellement ". $nombreTotalConnecté." connectés </p>
			<p>".$nombreMembreConnecté." membre et ".$nombreInvité." invité</p>";
			?>
		</div>
		<div class="div">
			<h3>
			<?php 
			echo $nomDuSite; 
			?>
			</h3>
			<p>
				Un projet opensource, pour réunir les informaticiens du monde entier et réaliser des projets
			</p>
			
		</div>
		<div class="div">
			<h3>Me contacter</h3>
			<ul>
				<li>Facbook / Twitter</li>
				<li>GitHub</li>
				<li>Site web perso</li>
			</ul>
		</div>
	</div>
	<div class="CopyRight">
	<q>Copyright 2016-2017 
	<?php echo $nomDuSite; ?></q>
		
	</div>

	
