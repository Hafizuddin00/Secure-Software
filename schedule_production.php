<?php
if(!isset($conn)){
	include 'includes/dbconnection.php' ;
	include 'db_connect.php' ;
}
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_categories" method="post">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">						
						<b class="text-muted">Production Information</b>
						<?php
						?>
						<div class="form-group">
							<label for="product_id">Product ID *</label>
							<select name="product_id" id="product_id" class="form-control form-control-sm" required="true">
								<option value="">Select Product ID</option>
								<?php 
								// Fetch Product IDs from the typeproduct table
								$sql2 = "SELECT * FROM typeproduct";
								$query2 = $dbh->prepare($sql2);
								$query2->execute();
								$result2 = $query2->fetchAll(PDO::FETCH_OBJ);

								foreach ($result2 as $row1) {          
								?>  
								<option value="<?php echo htmlentities($row1->product_id); ?>">
									<?php echo htmlentities($row1->product_id); ?> - <?php echo htmlentities($row1->product_name); ?>
								</option>
								<?php } ?> 
							</select>
						</div>

						<div class="form-group">
							<label for="recipe_name">Recipe Name *</label>
							<select name="recipe_name" id="recipe_name" class="form-control form-control-sm" required="true">
								<option value="">Select Recipe Name</option>
							</select>
						</div>

						<script>
							// JavaScript to handle the change event of the Product ID dropdown
							document.getElementById("product_id").addEventListener("change", function () {
								var product_id = this.value;  // Get the selected Product ID
								var recipeSelect = document.getElementById("recipe_name");  // Get the Recipe Name dropdown

								// Clear previous options
								recipeSelect.innerHTML = "<option value=''>Select Recipe Name</option>";

								if (product_id !== "") {
									// Make an AJAX request to fetch recipes based on the selected Product ID
									var xhr = new XMLHttpRequest();
									xhr.open("GET", "get_recipes.php?product_id1=" + product_id, true);
									xhr.onload = function () {
										if (xhr.status === 200) {
											try {
												var recipes = JSON.parse(xhr.responseText);  // Parse JSON response
												recipes.forEach(function (recipe) {
													var option = document.createElement("option");
													option.value = recipe.recipe_name;  // Use recipe name as value
													option.textContent = recipe.recipe_name;  // Set display text
													recipeSelect.appendChild(option);  // Append to dropdown
												});
											} catch (e) {
												console.error("Error parsing JSON response:", e);
											}
										} else {
											console.error("Error fetching recipes:", xhr.status, xhr.responseText);
										}
									};
									xhr.onerror = function () {
										console.error("AJAX request failed.");
									};
									xhr.send();
								}
							});
						</script>
						<div class = "form-group">
						<label class="control-label">Quantity Product *</label>
						<input type="number" name="qty_product" class="form-control" required placeholder="Enter Quantity Product" value="<?php echo isset($qty_product) ? $qty_product : '' ?>">
						</div>
						<div class="form-group">
							<label for="staff_id">Assigned to Staff ID (Leader) *</label>
							<select name="staff_id" id="staff_id" class="form-control form-control-sm" required="true">
								<option value="">Select Staff ID</option>
								<?php 
								// Fetch Product IDs from the typeproduct table
								$sql2 = "SELECT * FROM staff_information";
								$query2 = $dbh->prepare($sql2);
								$query2->execute();
								$result2 = $query2->fetchAll(PDO::FETCH_OBJ);

								foreach ($result2 as $row1) {          
								?>  
									<option value="<?php echo htmlentities($row1->staff_id); ?>">
										<?php echo htmlentities($row1->staff_id); ?>
									</option>
								<?php } ?> 
							</select>
						</div>
						</div>	
						<div class ="col-md-6">
						<div class="form-group">
							<label class="control-label">Date *</label>
							<input type="date" name="starteddate" class="form-control" required value="<?php echo isset($starteddate) ? $starteddate : '' ?>">
						</div>
							<div class="form-group">
								<label class="control-label">Started Time *</label>
								<input type="time" name="estimationduration" class="form-control" required value="<?php echo isset($estimationduration) ? $estimationduration : '' ?>">
							</div>
							<div class = "form-group">
								<label class="control-label">Estimated Duration *</label>
								<input type="number" name="hours" class="form-control" required placeholder="Enter duration in " value="<?php echo isset($hours) ? $hours : '' ?>">
							</div>
							<div class="form-group">
    						<label for="status">Status (Baker will update the status)</label>
							<input type="text" name="status" class="form-control" required value="UNFINISHED" readonly>
							</div>

							
						</div>
					</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button type="submit" name="submit" class="btn btn-success mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=production'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$('#manage_categories').submit(function(e){
    e.preventDefault();
    $('input').removeClass("border-danger");
	start_load();
    $('#msg').html('');  // Clear any previous message

    $.ajax({
        url: 'ajax.php?action=save_categories',  // Ensure this path is correct
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        success: function(resp){
           
            if(resp != 1){
                alert_toast('Data successfully saved.', "success");  // Trigger the success toast
				setTimeout(function(){
						location.replace('index.php?page=production')
					},1500) // Give time for the toast to show
            } else {
                // If there's an error, log it for debugging
                console.log('Error: ', resp);  
                $('#response-message').html('Failed to save the category.');  // Display error message on page
            }
        },
        error: function(xhr, status, error){
            console.log('AJAX Error: ', status, error);  // Log any AJAX errors
            $('#response-message').html('An error occurred while saving the category.');
        }
    });
});
</script>