<?php
		$instanceArray = array();
		$user = new User();
		$tempArray = array();

		foreach ($user->allAsArray() as $tempArray) {

			$privilege = new Privilege(new NamedArguments(array('primaryKey' => $tempArray['privilegeID'])));

			$tempArray['priv'] = $privilege->shortName;

			array_push($instanceArray, $tempArray);
		}



		if (count($instanceArray) > 0){
			?>
			<div class="adminRightHeader">Users</div>
			<table class='linedDataTable' style='width:570px;margin-bottom:5px;'>
				<tr>
				<th>Login ID</td>
				<th>First Name</td>
				<th>Last Name</td>
				<th>Privilege</td>
				<th>View Accounts</td>
				<th>Email Address</td>
				<th>&nbsp;</td>
				<th>&nbsp;</td>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					if ($instance['accountTabIndicator'] == '1') {
						$accountTab = 'Y';
					}else{
						$accountTab = 'N';
					}

					echo "<tr>";
					echo "<td>" . $instance['loginID'] . "</td>";
					echo "<td>" . $instance['firstName'] . "</td>";
					echo "<td>" . $instance['lastName'] . "</td>";
					echo "<td>" . $instance['priv'] . "</td>";
					echo "<td>" . $accountTab . "</td>";
					echo "<td>" . $instance['emailAddress'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=" . $instance['loginID'] . "&height=275&width=315&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit user'></a></td>";
					echo "<td><a href='javascript:deleteUser(\"" . $instance['loginID'] . "\")'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=&height=275&width=315&modal=true' class='thickbox' id='addUser'>add new user</a>
			<?php

		}else{
			echo "(none found)<br /><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=&height=275&width=315&modal=true' class='thickbox' id='addUser'>add new user</a>";
		}

		break;









