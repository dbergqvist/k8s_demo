var guestbookApp = angular.module('guestbook', ['ui.bootstrap']);

/**
 * Constructor
 */
function GuestbookController() {}

GuestbookController.prototype.newMessage = function() {
    this.scope_.messages.push(this.scope_.msg);
    var value = this.scope_.msg;
    this.scope_.msg = "";
    this.http_.post("/index.php", {message: value}) 
            .success(angular.bind(this, function(data) {
                this.scope_.response = "Updated.";
            }));
};

guestbookApp.controller('GuestbookCtrl', function ($scope, $http, $location) {
    $scope.controller = new GuestbookController();
    $scope.controller.scope_ = $scope;
    $scope.controller.location_ = $location;
    $scope.controller.http_ = $http;

    $scope.controller.http_.get("/index.php")
        .success(function(data) {
            console.log(data);
            var messages = [];
            for (var i=0; i < data.data.length; i++) {
                messages.push(data.data[i].message);
            }
            $scope.messages = messages;
        });
});
