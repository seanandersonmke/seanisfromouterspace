<div class="container">
    <h3 class="text-center">Form fun</h3>
        <div class="col-md-12">
        <p class="well well-sm">Basic form example. The submit button is disabled until the password fields match. The email address displays a different error message if not filled or has an invalid email address format. On click of the submit button, the data is sent through an AngularJs service to a PHP script that validates the inputs and sends back either the proper error message or sends back the entered password hashed by PHP. If successful, another field is revealed allowing me to demonstrate the PHP password_verify function. I am capable of sanitizing/validating inputs and generating error messages server and browser side. I have worked extensively with SQL databases and have also set up and used MongoDb. I can also write SQL.</p>
        </div>

        <div class="col-md-6">
            <form role="form" novalidate>
                <div class="form-group">
                    <span class="label label-primary">Email</span>
                    <input type="text" class="form-control" ng-model="user.email">
                </div>
                <div class="form-group">
                    <span class="label label-primary">Message</span>    
                    <input type="text" class="form-control" ng-model="user.message">
                </div>
                <div class="form-group">
                    <span class="label label-primary">Password</span>
                    <div class="input-group">    
                        <input type="password" class="form-control" ng-change="passwordVerify()" ng-model="user.password">
                        <span ng-class="valColor ? 'bg-success' : 'bg-danger'" class="input-group-addon"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>      
                    </div>
                </div> 
                <div class="form-group">
                    <span class="label label-primary">Verify Password</span>
                    <div class="input-group">     
                        <input type="password" ng-change="passwordVerify()" class="form-control" ng-model="user.password_verify">
                        <span ng-class="valColor ? 'bg-success' : 'bg-danger'" class="input-group-addon"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </span>
                    </div>
                </div>     
                    <input style="margin-bottom:20px; margin-top:20px" ng-disabled="passSubmit" type="submit" class="btn btn-primary" ng-click="formSubmit(user)">
            </form>
        </div>
        <div class="col-md-6">
        <h4>Angular data-binding</h4>
            <ul class="list-group">
                <li class="list-group-item">Your email input: <span class="text-success">{{user.email}}</span></li>
                <li class="list-group-item">Your message input: <span class="text-success">{{user.message}}</span></li>
            </ul>
            <div ng-show="showPassArea">
                <ul class="list-group">
                    <li class="list-group-item break-word">Filtered message: <span class="text-success">{{pass.message}}</span></li>
                    <li class="list-group-item break-word">Your password hash: <span class="text-success">{{pass.pwHash}}</span></li>
                    <li class="list-group-item">Password Match: <span class="text-success">{{pass.password}}</span><div class="pull-right check_square_danger" ng-class="checkColor ? 'bg-success' : 'bg-danger'"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></div></li>
                    <li class="list-group-item break-word">
                        <form role="form" novalidate>
                            <span class="label label-primary">Enter password to detect match:</span>
                            <input class="form-control" ng-change="passSubmitFn(pass)" ng-model="pass.password" type="text">
                        </form>
                    </li>
                </ul>
            </div>
            <p class='text-danger'>{{errors}}</p>
        </div>
    </div>