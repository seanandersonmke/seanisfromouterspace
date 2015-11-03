app.factory('services', function($http) {
    return {
        getMap: function(data) {
            return $http({
                    url: 'js/phpService.php', 
                    method: "POST",
                    data: {data: data},
                  });
        },
        getTweets: function(data) {
            return $http({
                    url: 'services/phpservices.php', 
                    method: "POST",
                    data: {fn: 'twitter', twitter_name: data},
                  });
        },
        getMovies: function(searchterm) {
            var data = {};
            data.fn = 'movies';
            data.searchterm = searchterm;
            return $http({
                    url: 'services/phpservices.php', 
                    method: "POST",
                    data: {data: data},
                  });
        },
        submitData: function(data){
            return $http({
                    url: 'services/phpservices.php', 
                    method: "POST",
                    data: {fn: 'pass_submit', form_data: data},
                  });
        },
        getData: function(data) {
            return $http({
                    url: 'services/phpservices.php', 
                    method: "POST",
                    data: {data: data},
                  });
        },
    }
});