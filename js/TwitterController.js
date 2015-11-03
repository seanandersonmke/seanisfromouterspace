app.controller('TwitterController', ['$scope', 'services', function($scope, services) {
	$scope.tweetname = 'LindseySnell';

	services.getTweets($scope.tweetname).success(function(data){
		console.log(data);
		$scope.tweets = data;
		//$scope.tweets.date = data.date
		//$scope.tweets.date = data.date;
		
		//$scope.tweets.ent
		//console.log($scope.tweets.date);
		//console.log($scope.tweets.media_url);
	});
}]);