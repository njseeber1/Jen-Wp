
<?php
/*
* Plugin Name: WordPress ShortCode
* Description: Create your WordPress shortcode.
* Version: 1.0
* Author: Nick Seeber
* Author URI: 
*/


function Create(){
	?>



	<div ng-app="myApp" ng-controller="myController1">
	<H4>Current User Email: {{CurrentUser.user_email}}</h4>
		<ul>
			<li ng-repeat="x in TestArray">{{x}}</li>
		</ul>
	</div>
	<?php wp_get_post_categories( ) ?>



	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
	<script>
	var app = angular.module("myApp", []); 
	app.controller("myController1", function($scope, $http) {
		$scope.TestArray = ["Test1", "Test2", "Test3"];
		$scope.CurrentUser = {};
		$scope.ControllerEndpoint = "/wp-content/plugins/Nick-WPPlugin/controllers/homeController.php?method=";


		$scope.GetData = function(){
			$http({method:'GET',url:$scope.ControllerEndpoint + "Gethomepage"}).then(function(data){
				$scope.CurrentUser = data.data;
				console.log($scope.CurrentUser);
		});
		}
		$scope.GetData();
	});
	</script>
	<?php
	}
	add_shortcode('test', 'Create');
