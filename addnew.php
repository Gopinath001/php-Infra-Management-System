<div class="sidebar">
	<div class="ul-container">
		<div class="ul" >
			<ul>
				<li >
					<a href="users.php"><span class="container-cate">
					DashBoard</a>
					</span>
				</li>
				<li class="li-active" >
					<a href="users.php?id=add"><span class="container-cate">
					Add new Items</a>
				</span></li>
				<li><span class="container-cate">
					Delete Items
				</span></li>
				<li><span class="container-cate">
					Loan Items
				</span></li>
				<li><span class="container-cate">
					Approve users
				</span></li>
			</ul>
		</div>
	</div>

</div>

<div class="dashboard-container">
	<div class="overall-container">
	<div class="dashboard">
		<span>
			There are two categorys. Choose pc or Other devices <br><br>

			Choose appropriate option then click on get field to further proceed.
			
			
			<?php if(!empty(@$_SESSION['add_status'])):?>
				<?php 
				$ts = $_SESSION['add_status'];
				unset($_SESSION['add_status']);	
				?>
				<br><br>
				<span style="background-color: yellow; color: black;"><?php echo $ts; ?></span>
			<?php endif; ?>    
		</span>
	</div>
	<div class="search-container">
		<div class="search" id="category_selector">
			 <div><select name="addDevices" id="addDevice">
			 	<option value="" disabled selected>What?</option>
    			<option value="pc">PC</option>
    			<option value="device">Other Devices</option>
  			</select>
  			</div>	
		</div>
		<div class="search-go">
			<span id="add" name="add" class="btnright" onclick="getAttributes_to_add()" >Get Fields</span>
		</div>
	</div>
	
	<div id="fields_container">

	</div>
</div>