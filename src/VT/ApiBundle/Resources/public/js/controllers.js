'use strict';

/* Controllers */

var app = angular.module('front', [
	'ngRoute',
	'ngAnimate',
    'flash',
    'ngAnimate',
    'ngFileUpload',
    'ngActivityIndicator',
    'ui.bootstrap'
]);

// manage routes
app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    // Routing system
    $routeProvider
        .when('/login', {
            templateUrl: Routing.generate('login'),
            controller: 'AuthController'
        })
        .when('/logout', {
            templateUrl: Routing.generate('ard_backend_checkout'),
            controller: 'LogoutController'
        })
        .when('/', {
            templateUrl: Routing.generate('ard_backend_checkout'),
            controller: 'RoomTeacherController'
        })
        .when('/roomTeachers', {
            templateUrl: Routing.generate('ard_backend_checkout'),
            controller: 'RoomTeacherController'
        })
        .when('/roomTeachers/filing', {
            templateUrl: Routing.generate('innova_platin_page_depot_document'),
            controller: 'FilingController'
        })
        .when('/roomTeachers/support', {
            templateUrl: Routing.generate('innova_platin_page_support'),
            controller: 'SupportController'
        }) 
        .when('/roomTeachers/forum', {
            templateUrl: Routing.generate('innova_platin_page_forum'),
            controller: 'ForumController'
        })
        .when('/roomTeachers/contribution/subject/:subjectId', {
            templateUrl: Routing.generate('innova_platin_page_contribution'),
            controller: 'ContributionSubjectController'
        })
        .when('/profile', {
            templateUrl: Routing.generate('ard_backend_profile'),
            controller: 'ProfileController'
        })
        .when('/professors', {
            templateUrl: Routing.generate('platin_backend_professors'),
            controller: 'ProfessorsController'
        })
        .otherwise({
            redirectTo: '/login'
        });
        $locationProvider.html5Mode(true);
}]);

/**
 * [Allows to search name from our venuesName array]
 * @param  {Array}  ) { return function (items, props) { var out
 * 
 * @return {[Array]}
 */
app.filter('propsFilter', function () {
    return function (items, props) {
        var out = [];

        if (angular.isArray(items)) {
            items.forEach(function (item) {
                var itemMatches = false;

                var keys = Object.keys(props);
                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();
                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    }
});

app.filter('startFrom', function() {
 return function(input, start) {
 if(input) {
 start = +start; //parse to int
 return input.slice(start);
 }
 return [];
 }
});

/**
 * [directive allow to trigger blur and focus events]
 * @param  {[ngHasfocus]} ) {
 * 
 * @return
 */
app.directive('ngHasfocus', function() {
    return function(scope, element, attrs) {
        scope.$watch(attrs.ngHasfocus, function (nVal, oVal) {
            if (nVal)
                element[0].focus();
        });
        element.bind('blur', function() {
            scope.$apply(attrs.ngHasfocus + " = false");
        });
        
        element.bind('keydown', function (e) {
            if (e.which == 13)
                scope.$apply(attrs.ngHasfocus + " = false");
        });
    }
});

app.directive('myEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.myEnter);
                });

                event.preventDefault();
            }
        });
    };
});

/**
 * [directive that creates a confirmation dialog for an action]
 * @param  {[ngReallyClick]} ) {
 * 
 * @return 
 */
app.directive('ngReallyClick', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', function() {
                var message = attrs.ngReallyMessage;
                if (message && confirm(message)) {
                    scope.$apply(attrs.ngReallyClick);
                }
            });
        }
    }
}]);

/**
 * [HeaderController allow to manage data in ng-controller HeaderController template]
 * @param {[$scope]} $scope 
 * @param {[$http]} $http   
 * @param {[$routeParams]} $routeParams 
 * @param {[mySharedService]} mySharedService
 * @param {[$location]} $location)      {
 */
app.controller('HeaderController', ['$scope', '$http', '$routeParams', '$location', function HeaderController($scope, $http, $routeParams, $location) {
    /**
     * [isActive allow to change active tab]
     * @param  {[viewLocation]}  viewLocation 
     * 
     * @return {Boolean}
     */
    $scope.isActive = function (viewLocation) {
        if (($location.path() === viewLocation))
        {
            return true;
        }
    };
}
]);
app.controller('ContributionSubjectController', ['$scope', '$http', '$routeParams', 'Flash', '$timeout', 'Upload', function ContributionSubjectController($scope, $http, $routeParams, Flash, $timeout, Upload) {
    var subjectId = $routeParams.subjectId;
    $scope.addNewContribution = false;
    var url_get_contributions = Routing.generate('platin_get_contributions', {subject: subjectId});
    var url_get_subject_data = Routing.generate('platin_get_subject_data', {subject: subjectId});
    $http.get(url_get_subject_data).success(function (data) {
        $scope.subject = data;
    });
    $http.get(url_get_contributions).success(function (data) {
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
    });

    $scope.deleteContribution = function (contributionId) {
        var url_delete_contribution = Routing.generate('platin_delete_contribution', {contribution: contributionId});
        $http.get(url_delete_contribution)
            .success(function (data) {
                $http.get(url_get_subject_data).success(function (data) {
                    $scope.subject = data;
                });
                $http.get(url_get_contributions).success(function (data) {
                    $scope.list = data;
                    $scope.currentPage = 1; //current page
                    $scope.entryLimit = 5; //max no of items to display in a page
                    $scope.filteredItems = $scope.list.length; //Initially for no filter
                    $scope.totalItems = $scope.list.length;
                });
                Flash.create('success', "La contribution a été supprimée avec succès", 'customAlert');
            })
            .error(function (data) {
                console.log(data);
            });
    }
    function PostBlob(blob, fileType, fileName) {
        // FormData
        var idsujet = $routeParams.subjectId;
        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append(fileType + '-blob', blob);
        formData.append('idsujet', idsujet);
        if ((angular.isUndefined($scope.infos))) 
        {
           formData.append('infos', "empty"); 
        }
        else
        {
            formData.append('infos', $scope.infos);
        }
        // progress-bar
        var hr = document.createElement('hr');
        container.appendChild(hr);
        var strong = document.createElement('strong');
        strong.id = 'percentage';
        strong.innerHTML = fileType + ' upload progress: ';
        container.appendChild(strong);
        var progress = document.createElement('progress');
        container.appendChild(progress);

        // POST the Blob using XHR2
        xhr("/roomTeachers/contribution/add/video", formData, progress, percentage, function(fileURL) {
            container.appendChild(document.createElement('hr'));
            var mediaElement = document.createElement(fileType);
            
            var source = document.createElement('source');
            var href = location.href.substr(0, location.href.lastIndexOf('/') + 1);
            source.src = href + fileURL;
            
            if(fileType == 'video') source.type = 'video/webm; codecs="vp8, vorbis"';
            if(fileType == 'audio') source.type = !!navigator.mozGetUserMedia ? 'audio/ogg': 'audio/wav';
            
            // mediaElement.appendChild(source);
            
            // mediaElement.controls = true;
            // container.appendChild(mediaElement);
            // mediaElement.play();

            $http.get(url_get_contributions).success(function (data) {
                $scope.list = data;
                $scope.currentPage = 1; //current page
                $scope.entryLimit = 5; //max no of items to display in a page
                $scope.filteredItems = $scope.list.length; //Initially for no filter
                $scope.totalItems = $scope.list.length;
            });
            progress.parentNode.removeChild(progress);
            strong.parentNode.removeChild(strong);
            hr.parentNode.removeChild(hr);
        });
            // location.reload();
    }
    var record = document.getElementById('record');
    var stop = document.getElementById('stop');
    var deleteFiles = document.getElementById('delete');
    var audio = document.querySelector('audio');
    var recordVideo = document.getElementById('record-video');
    var preview = document.getElementById('preview');
    var container = document.getElementById('container');
    var isFirefox = !!navigator.mozGetUserMedia;

    var recordAudio, recordVideo;
    record.onclick = function() {
        record.disabled = true;
        navigator.getUserMedia({
                audio: false,
                video: true
            }, function(stream) {
                preview.src = window.URL.createObjectURL(stream);
                preview.play();
                recordAudio = RecordRTC(stream, {
                    onAudioProcessStarted: function() {
                        if(!isFirefox) {
                            recordVideo.startRecording();
                        }
                    }
                });
                
                if(isFirefox) {
                    recordAudio.startRecording();
                }
                
                if(!isFirefox) {
                    recordVideo = RecordRTC(stream, {
                        type: 'video'
                    });
                    recordAudio.startRecording();
                }

                stop.disabled = false;
            }, function(error) {
                alert( JSON.stringify (error, null, '\t') );
            });
    };
    var fileName;
    stop.onclick = function() {
        record.disabled = false;
        stop.disabled = true;
        
        preview.src = '';

        fileName = Math.round(Math.random() * 99999999) + 99999999;
        
        if(!isFirefox) {
            recordAudio.stopRecording();
            PostBlob(recordAudio.getBlob(), 'audio', fileName + '.wav');
        }
        else {
            recordAudio.stopRecording( function(url) {
                preview.src = url;
                PostBlob(recordAudio.getBlob(), 'video', fileName + '.webm');
            });
        }

        if(!isFirefox) {
            recordVideo.stopRecording();
            PostBlob(recordVideo.getBlob(), 'video', fileName + '.webm');
        }

        deleteFiles.disabled = false;
    };

    function deleteAudioVideoFiles() {
        deleteFiles.disabled = true;
        if (!fileName) return;
        var formData = new FormData();
        formData.append('delete-file', fileName);
        xhr('delete.php', formData, null, null, function(response) {
            console.log(response);
        });
        fileName = null;
        container.innerHTML = '';
    }

    function xhr(url, data, progress, percentage, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                callback(request.responseText);
            }
        };
        
        if(url.indexOf('delete.php') == -1) {
            request.upload.onloadstart = function() {
                percentage.innerHTML = 'Upload started...';
            };
            
            request.upload.onprogress = function(event) {
                progress.max = event.total;
                progress.value = event.loaded;
                percentage.innerHTML = 'Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%";
            };
            
            request.upload.onload = function() {
                percentage.innerHTML = 'Saved!';
            };
        }
        
        request.open('POST', url);
        request.send(data);
    }

    window.onbeforeunload = function() {
        if (!!fileName) {
            deleteAudioVideoFiles();
            return "Il semble que vous n'avez pas supprimé les fichiers audio/vidéo du serveur";
        }
    };
}
]);

app.controller('ForumController', ['$scope', '$http', '$routeParams', 'Flash', '$timeout', 'Upload', function ForumController($scope, $http, $routeParams, Flash, $timeout, Upload) {
    $scope.addNewSubject = false;
    $http.get('/ajax/subjects').success(function (data) {
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
    });

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.filter = function() {
        $timeout(function() {
        $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };

    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.processAddSubjectForm = function() {
        var url_add_subject = Routing.generate('platin_add_subject');
        var subjectData = new FormData(); 
        subjectData.append('title', $scope.title);
        subjectData.append('description', $scope.description);
        $http({
            method: 'POST',
            url: url_add_subject,
            data: subjectData,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }) 
        .success(function (data) {
            Flash.create(data['type'], data['message'], 'customAlert');
            $scope.title = undefined;
            $scope.description = undefined;
            $http.get('/ajax/subjects').success(function (data) {
                $scope.list = data;
                $scope.currentPage = 1; //current page
                $scope.entryLimit = 5; //max no of items to display in a page
                $scope.filteredItems = $scope.list.length; //Initially for no filter
                $scope.totalItems = $scope.list.length;
            });
        });
    }

    $scope.deleteSubject = function (subjectId) {
        var url_delete_subject = Routing.generate('platin_delete_subject', {subject: subjectId});
        $http.get(url_delete_subject)
            .success(function (data) {
                $http.get('/ajax/subjects').success(function (data) {
                    $scope.list = data;
                    $scope.currentPage = 1; //current page
                    $scope.entryLimit = 5; //max no of items to display in a page
                    $scope.filteredItems = $scope.list.length; //Initially for no filter
                    $scope.totalItems = $scope.list.length;
                });
                Flash.create('success', "Le sujet a été supprimé avec succès", 'customAlert');
            })
            .error(function (data) {
                console.log(data);
            });
    }
}
]);    


app.controller('SupportController', ['$scope', '$http', '$routeParams', 'Flash', '$timeout', 'Upload', function SupportController($scope, $http, $routeParams, Flash, $timeout, Upload) {

    $http.get('/ajax/files').success(function (data) {
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
    });

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.filter = function() {
        $timeout(function() {
        $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };

    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.deleteImage = function (imageId) {
        var url_delete_file = Routing.generate('platin_delete_file', {imageId: imageId});
        $http.get(url_delete_file)
            .success(function (data) {
                $http.get('/ajax/files').success(function (data) {
                    $scope.list = data;
                    $scope.currentPage = 1; //current page
                    $scope.entryLimit = 5; //max no of items to display in a page
                    $scope.filteredItems = $scope.list.length; //Initially for no filter
                    $scope.totalItems = $scope.list.length;
                });
                Flash.create('success', "Le fichier a été supprimé avec succès", 'customAlert');
            })
            .error(function (data) {
                console.log(data);
            });
    }

    $scope.downloadData = function (fileId) {
        window.location = '/roomTeachers/recovery/download/'+fileId;
    }
}
]);   

app.controller('FilingController', ['$scope', '$http', '$routeParams', 'Flash', '$timeout', 'Upload', function FilingController($scope, $http, $routeParams, Flash, $timeout, Upload) {
    // public
    var publicCategoriesSelected = [];
    $scope.publicCategoriesSelected  = [];
    // niveau
    var niveauCategoriesSelected = [];
    $scope.niveauCategoriesSelected  = [];
    // objectif
    var objectifCategoriesSelected = [];
    $scope.objectifCategoriesSelected  = [];
    // theme
    var themeCategoriesSelected = [];
    $scope.themeCategoriesSelected  = [];
    // autre
    var autreCategoriesSelected = [];
    $scope.autreCategoriesSelected  = [];

    $scope.fileAdded = false;

    $scope.addPublic = function()
    {
        var addToArray=true;
        for(var i=0;i<$scope.publicCategoriesSelected.length;i++){
            if($scope.publicCategoriesSelected[i].categoryName===$scope.tagPublic){
                addToArray=false;
            }
        }
        if(addToArray){
            publicCategoriesSelected.push({categoryId: 0, categoryName: $scope.tagPublic});
        }
        $scope.tagPublic = undefined;
        $scope.publicCategoriesSelected = publicCategoriesSelected;
    }
    $scope.addNiveau = function()
    {
        var addToArray=true;
        for(var i=0;i<$scope.niveauCategoriesSelected.length;i++){
            if($scope.niveauCategoriesSelected[i].categoryName===$scope.tagNiveau){
                addToArray=false;
            }
        }
        if(addToArray){
            niveauCategoriesSelected.push({categoryId: 0, categoryName: $scope.tagNiveau});
        }
        $scope.tagNiveau = undefined;
        $scope.niveauCategoriesSelected = niveauCategoriesSelected;
    }
    $scope.addTheme = function()
    {
        var addToArray=true;
        for(var i=0;i<$scope.themeCategoriesSelected.length;i++){
            if($scope.themeCategoriesSelected[i].categoryName===$scope.tagTheme){
                addToArray=false;
            }
        }
        if(addToArray){
            themeCategoriesSelected.push({categoryId: 0, categoryName: $scope.tagTheme});
        }
        $scope.tagTheme = undefined;
        $scope.themeCategoriesSelected = themeCategoriesSelected;
    }
    $scope.addObjectif = function()
    {
        var addToArray=true;
        for(var i=0;i<$scope.objectifCategoriesSelected.length;i++){
            if($scope.objectifCategoriesSelected[i].categoryName===$scope.tagObjectif){
                addToArray=false;
            }
        }
        if(addToArray){
            objectifCategoriesSelected.push({categoryId: 0, categoryName: $scope.tagObjectif});
        }
        $scope.tagObjectif = undefined;
        $scope.objectifCategoriesSelected = objectifCategoriesSelected;
    }
    $scope.addAutre = function()
    {
        var addToArray=true;
        for(var i=0;i<$scope.autreCategoriesSelected.length;i++){
            if($scope.autreCategoriesSelected[i].categoryName===$scope.tagAutre){
                addToArray=false;
            }
        }
        if(addToArray){
            autreCategoriesSelected.push({categoryId: 0, categoryName: $scope.tagAutre});
        }
        $scope.tagAutre = undefined;
        $scope.autreCategoriesSelected = autreCategoriesSelected;
    }

    /**
     * [stringIsNumber check if value in parameters is a number]
     * @param  {[String]} s 
     * 
     * @return {[Boolean]}
     */
    function stringIsNumber(s) {
        var x = +s; // made cast obvious for demonstration
        return x.toString() === s;
    }

    $scope.processNewFileForm = function()
    {
        if ((angular.isUndefined($scope.fileName))) 
        {
            Flash.create('danger', "Le nom du fichier est obligatoire", 'customAlert');
        }
        else if ((angular.isUndefined($scope.imageId)) || $scope.imageId == 0) 
        {
            // Flash.create('success', message); // other format to show error 
            Flash.create('danger', "Le fichier est obligatoire !!!", 'customAlert');
        }
        else
        {
            var url_post_file_data = Routing.generate('ard_post_file_data');
            var newFileData = new FormData();
            newFileData.append('filename', $scope.fileName);
            newFileData.append('imageId', $scope.imageId);
            $http({
                method: 'POST',
                url: url_post_file_data,
                data: newFileData,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }) 
            .success(function (data) {
                if(angular.isNumber(data))
                {
                    var url_post_tags = Routing.generate('platin_post_tags');
                    angular.forEach($scope.publicCategoriesSelected, function (value, key) {
                        var newTagData = new FormData();
                        newTagData.append('categoryId', value['categoryId']);
                        newTagData.append('categoryName', value['categoryName']);
                        newTagData.append('typeId', 1);
                        newTagData.append('docId', data);
                        $http({
                            method: 'POST',
                            url: url_post_tags,
                            data: newTagData,
                            transformRequest: angular.identity,
                            headers: {'Content-Type': undefined}
                        }) 
                        .success(function (data) {
                        })
                    });
                    angular.forEach($scope.niveauCategoriesSelected, function (value, key) {
                        var newTagData = new FormData();
                        newTagData.append('categoryId', value['categoryId']);
                        newTagData.append('categoryName', value['categoryName']);
                        newTagData.append('typeId', 2);
                        newTagData.append('docId', data);
                        $http({
                            method: 'POST',
                            url: url_post_tags,
                            data: newTagData,
                            transformRequest: angular.identity,
                            headers: {'Content-Type': undefined}
                        }) 
                        .success(function (data) {
                        })
                    });
                    angular.forEach($scope.themeCategoriesSelected, function (value, key) {
                        var newTagData = new FormData();
                        newTagData.append('categoryId', value['categoryId']);
                        newTagData.append('categoryName', value['categoryName']);
                        newTagData.append('typeId', 3);
                        newTagData.append('docId', data);
                        $http({
                            method: 'POST',
                            url: url_post_tags,
                            data: newTagData,
                            transformRequest: angular.identity,
                            headers: {'Content-Type': undefined}
                        }) 
                        .success(function (data) {
                        })
                    });
                    angular.forEach($scope.objectifCategoriesSelected, function (value, key) {
                        var newTagData = new FormData();
                        newTagData.append('categoryId', value['categoryId']);
                        newTagData.append('categoryName', value['categoryName']);
                        newTagData.append('typeId', 5);
                        newTagData.append('docId', data);
                        $http({
                            method: 'POST',
                            url: url_post_tags,
                            data: newTagData,
                            transformRequest: angular.identity,
                            headers: {'Content-Type': undefined}
                        }) 
                        .success(function (data) {
                        })
                    });
                    angular.forEach($scope.autreCategoriesSelected, function (value, key) {
                        var newTagData = new FormData();
                        newTagData.append('categoryId', value['categoryId']);
                        newTagData.append('categoryName', value['categoryName']);
                        newTagData.append('typeId', 6);
                        newTagData.append('docId', data);
                        $http({
                            method: 'POST',
                            url: url_post_tags,
                            data: newTagData,
                            transformRequest: angular.identity,
                            headers: {'Content-Type': undefined}
                        }) 
                        .success(function (data) {
                        })
                    });
                    $http.get('/ajax/categories/public')
                    .success(function (data) {
                        $scope.publicCategories = data;
                    });
                    $http.get('/ajax/categories/niveau')
                    .success(function (data) {
                        $scope.niveauCategories = data;
                    });
                    $http.get('/ajax/categories/theme')
                    .success(function (data) {
                        $scope.themeCategories = data;
                    });
                    $http.get('/ajax/categories/objectif')
                    .success(function (data) {
                        $scope.objectifCategories = data;
                    });
                    $http.get('/ajax/categories/autre')
                    .success(function (data) {
                        $scope.autreCategories = data;
                    });
                    $scope.fileAdded = false;
                    publicCategoriesSelected = [];
                    $scope.publicCategoriesSelected  = [];
                    niveauCategoriesSelected = [];
                    $scope.niveauCategoriesSelected  = [];
                    themeCategoriesSelected = [];
                    $scope.themeCategoriesSelected  = [];
                    objectifCategoriesSelected = [];
                    $scope.objectifCategoriesSelected  = [];
                    autreCategoriesSelected = [];
                    $scope.autreCategoriesSelected  = [];
                    $scope.fileName = undefined;
                    Flash.create('success', "Votre fichier a été ajouté avec succès" , 'customAlert');
                }
                else
                {
                    Flash.create('danger', "Un problème est survenue lors de l'ajout" , 'customAlert');
                }
            });
        }
    }

    $scope.deletePublicCategory = function (index) {
        $scope.publicCategoriesSelected.splice( index, 1);
    }
    $scope.deleteNiveauCategory = function (index) {
        $scope.niveauCategoriesSelected.splice( index, 1);
    }
    $scope.deleteThemeCategory = function (index) {
        $scope.themeCategoriesSelected.splice( index, 1);
    }
    $scope.deleteObjectifCategory = function (index) {
        $scope.objectifCategoriesSelected.splice( index, 1);
    }
    $scope.deleteAutreCategory = function (index) {
        $scope.autreCategoriesSelected.splice( index, 1);
    }
    $scope.deleteImage = function (imageId) {
        var url_delete_file = Routing.generate('platin_delete_file', {imageId: imageId});
        $http.get(url_delete_file)
            .success(function (data) {
                $scope.fileAdded = false;
                $scope.imageId = 0;
                Flash.create('success', "Le fichier a été supprimé avec succès", 'customAlert');
            })
            .error(function (data) {
                console.log(data);
            });
    }

    $scope.addNewSelectedPublicCategory = function(item)
    {
        var addToArray=true;
        for(var i=0;i<$scope.publicCategoriesSelected.length;i++){
            if($scope.publicCategoriesSelected[i].categoryName===item.categoryName){
                addToArray=false;
            }
        }
        if(addToArray){
            publicCategoriesSelected.push({categoryId: item.categoryId, categoryName: item.categoryName});
        }
        $scope.tag = undefined;
        $scope.publicCategoriesSelected = publicCategoriesSelected;
    }
    $scope.addNewSelectedNiveauCategory = function(item)
    {
        var addToArray=true;
        for(var i=0;i<$scope.niveauCategoriesSelected.length;i++){
            if($scope.niveauCategoriesSelected[i].categoryName===item.categoryName){
                addToArray=false;
            }
        }
        if(addToArray){
            niveauCategoriesSelected.push({categoryId: item.categoryId, categoryName: item.categoryName});
        }
        $scope.tag = undefined;
        $scope.niveauCategoriesSelected = niveauCategoriesSelected;
    }
    $scope.addNewSelectedThemeCategory = function(item)
    {
        var addToArray=true;
        for(var i=0;i<$scope.themeCategoriesSelected.length;i++){
            if($scope.themeCategoriesSelected[i].categoryName===item.categoryName){
                addToArray=false;
            }
        }
        if(addToArray){
            themeCategoriesSelected.push({categoryId: item.categoryId, categoryName: item.categoryName});
        }
        $scope.tag = undefined;
        $scope.themeCategoriesSelected = themeCategoriesSelected;
    }
    $scope.addNewSelectedObjectifCategory = function(item)
    {
        var addToArray=true;
        for(var i=0;i<$scope.objectifCategoriesSelected.length;i++){
            if($scope.objectifCategoriesSelected[i].categoryName===item.categoryName){
                addToArray=false;
            }
        }
        if(addToArray){
            objectifCategoriesSelected.push({categoryId: item.categoryId, categoryName: item.categoryName});
        }
        $scope.tag = undefined;
        $scope.objectifCategoriesSelected = objectifCategoriesSelected;
    }
    $scope.addNewSelectedAutreCategory = function(item)
    {
        var addToArray=true;
        for(var i=0;i<$scope.autreCategoriesSelected.length;i++){
            if($scope.autreCategoriesSelected[i].categoryName===item.categoryName){
                addToArray=false;
            }
        }
        if(addToArray){
            autreCategoriesSelected.push({categoryId: item.categoryId, categoryName: item.categoryName});
        }
        $scope.tag = undefined;
        $scope.autreCategoriesSelected = autreCategoriesSelected;
    }
    

    $http.get('/ajax/categories/public')
    .success(function (data) {
        $scope.publicCategories = data;
    });
    $http.get('/ajax/categories/niveau')
    .success(function (data) {
        $scope.niveauCategories = data;
    });
    $http.get('/ajax/categories/theme')
    .success(function (data) {
        $scope.themeCategories = data;
    });
    $http.get('/ajax/categories/objectif')
    .success(function (data) {
        $scope.objectifCategories = data;
    });
    $http.get('/ajax/categories/autre')
    .success(function (data) {
        $scope.autreCategories = data;
    });

    var url_add_file = Routing.generate('platin_post_photo');
    $scope.$watch('files', function () {
        $scope.upload($scope.files);
    });
    $scope.log = '';
    var files = [];
    $scope.imageId = 0;


    /**
     * [upload call the function that upload image to the server through image by image]
     * @param  {[FILE]} files
     * 
     * @return {[]}
     */
    $scope.upload = function (files) {
        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {
                $scope.progressBar = true;
                uploadUsingUpload(files[i]);
            }
        }
    };

    /**
     * [uploadUsingUpload allow to upload new image]
     * @param  {[File]} file 
     * 
     * @return {[String]} 
     */
    function uploadUsingUpload(file) {
        file.upload = Upload.upload({
            url: url_add_file,
            method: 'POST',
            headers: {'Content-Type': undefined},
            file: file,
            fileFormDataName: 'myFile'
        });

        file.upload.then(function (response) {
            $scope.imageId = response.data;
            $scope.fileAdded = true;
            $timeout(function () {
                file.result = response.data;
                $scope.progressBar = false;
            });
        }, function (response) {
            if (response.status > 0)
                $scope.errorMsg = response.status + ': ' + response.data;
        });
        // allow to show progress of upload
        file.upload.progress(function (evt) {
            // Math.min is to fix IE which reports 200% sometimes
            file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
        });

        file.upload.xhr(function (xhr) {
            xhr.upload.addEventListener('abort', function()
            {
                $scope.progressBar = false;
            },
            false);
        });
    }
}
]);

// GlobalController
app.controller('GlobalController', ['$scope', '$http', function GlobalController($scope, $http) {
    
}
]);

// AuthController
app.controller('AuthController', ['$scope', '$http', function AuthController($scope, $http) {
    
}
]);

// LogoutController
app.controller('LogoutController', ['$scope', '$http', function LogoutController($scope, $http) {
	console.log("logout");
    window.location = '/logout';
}
]);

// ProfileController
app.controller('ProfileController', ['$scope', '$http', 'Flash', function ProfileController($scope, $http, Flash) {
    var url_get_client_data = Routing.generate('platin_data_of_client');
    var url_setUserData = Routing.generate('platin_set_data_user');
    $http.get(url_get_client_data).success(function (data) {
       $scope.user = data;
    });
    /**
     * [updateUserName update user name]
     * @param  {[String]} name [user first name]
     * 
     * @return {[String]}
     */
    $scope.updateUserName = function (name, oldName) {
        if(name != oldName)
        {
            if (!angular.isUndefined(name) && name != "") {
               var userData = new FormData();
                userData.append('value', name);
                userData.append('type', 'name');
                $http({
                    method: 'POST',
                    url: url_setUserData,
                    data: userData,
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined}
                }) 
                .success(function (data) {
                    $scope.user.name = name;
                    Flash.create('success', data, 'customAlert');
                })
                .error(function (data) {
                    console.log(data);
                });
            }
            else
            {
                $scope.uName = oldName;
            }
        }
    }

    /**
     * [updateUserSurname update user surname]
     * @param  {[String]} surname [user last name]
     * 
     * @return {[String]}
     */
    $scope.updateUserSurname = function (surname, oldSurname) {
        if(surname != oldSurname)
        {
            if (!angular.isUndefined(surname) && surname != "") {
                var userData = new FormData();
                userData.append('value', surname);
                userData.append('type', 'surname');
                $http({
                    method: 'POST',
                    url: url_setUserData,
                    data: userData,
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined}
                }) 
                .success(function (data) {
                    $scope.user.surname = surname;
                    Flash.create('success', data, 'customAlert');
                })
                .error(function (data) {
                    console.log(data);
                });
            }
            else
            {
                $scope.uSurname = oldSurname;
            }
        }
    }

    /**
     * [updateUserEmail update user email]
     * @param  {[String]} email [user email]
     * 
     * @return {[String]}
     */
    $scope.updateUserEmail = function (email, oldEmail) {
        if(email != oldEmail)
        {
            if (!angular.isUndefined(email) && email != "") {
                var userData = new FormData();
                userData.append('value', email);
                userData.append('type', 'email');
                $http({
                    method: 'POST',
                    url: url_setUserData,
                    data: userData,
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined}
                }) 
                .success(function (data) {
                    $scope.user.email = email;
                    Flash.create('success', data, 'customAlert');
                })
                .error(function (data) {
                    console.log(data);
                });
            }
            else
            {
                $scope.uEmail = oldEmail;
            }
        }
    }

    /**
     * [updateUserPhone update user phone]
     * @param  {[String]} phone [user phone]
     * 
     * @return {[String]}
     */
    $scope.updateUserPhone = function (phone, oldPhone) {
        if(phone != oldPhone)
        {
            if (!angular.isUndefined(phone) && phone != "") {
                var userData = new FormData();
                userData.append('value', phone);
                userData.append('type', 'phone');
                $http({
                    method: 'POST',
                    url: url_setUserData,
                    data: userData,
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined}
                }) 
                .success(function (data) {
                    $scope.user.phone = phone;
                    Flash.create('success', data, 'customAlert');
                })
                .error(function (data) {
                    console.log(data);
                });
            }
            else
            {
                $scope.uPhone = oldPhone;
            }
        }
    }

    /**
     * [updateUserPassword check and update user password]
     * @param  {[String]} password    
     * @param  {[String]} confirmPassword 
     * 
     * @return {[String]}  
     */
    $scope.updateUserPassword = function (password, confirmPassword) {
        if (!angular.isUndefined(password) && !angular.isUndefined(confirmPassword)) {
            if (password == confirmPassword) 
            {
                var userData = new FormData();
                userData.append('value', password);
                userData.append('type', 'password');
                $http({
                    method: 'POST',
                    url: url_setUserData,
                    data: userData,
                    transformRequest: angular.identity,
                    headers: {'Content-Type': undefined}
                }) 
                .success(function (data) {
                    Flash.create('success', data, 'customAlert');
                })
                .error(function (data) {
                    console.log(data);
                });
            };
           
        }
    }
}
]);

// CheckoutController
app.controller('RoomTeacherController', ['$scope', '$http', 'Flash', '$timeout', function CheckoutController($scope, $http, Flash, $timeout) {
    
}
]);


// ProfessorsController
app.controller('ProfessorsController', ['$scope', '$http', 'Flash', '$timeout', function ProfessorsController($scope, $http, Flash, $timeout) {  
    var url_addUserData = Routing.generate('platin_add_new_user');
    var url_updateUserData = Routing.generate('platin_update_user');
    $scope.updateProfessor = false;
    $scope.addNewProfessor = false;
    $http.get('/ajax/professors').success(function (data) {
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
    });

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.filter = function() {
        $timeout(function() {
        $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };

    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.updateProfessorFct = function(userId) {
        $scope.updateProfessor = true;
        $scope.addNewProfessor = false;
        var url_get_user = Routing.generate('platin_get_user', {user: userId});
        $http.get(url_get_user)
            .success(function (data) {
                $scope.user = data;
            });
    }

    $scope.addProfessor = function() {
        $scope.addNewProfessor = true;
        $scope.updateProfessor = false;
    }

    $scope.processUpdateProfessor = function() {
        if (angular.isUndefined($scope.user.name)) 
        {
            Flash.create('danger', "Le nom de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.user.lastname)) 
        {
            Flash.create('danger', "Le prénom de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.user.email)) 
        {
            Flash.create('danger', "L'email de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.user.phone)) 
        {
            Flash.create('danger', "Le numéro de téléphone est obligatoire", 'customAlert');
        }
        else if (($scope.user.password != "") && ($scope.user.passwordConfirmation != "")) 
        {
            if ($scope.user.password != $scope.user.passwordConfirmation) 
            {
                Flash.create('danger', "Le mot de passe et sa confirmation doivent être identiques", 'customAlert');
            }  
        }
        else
        {
            var clientData = new FormData(); 
            clientData.append('name', $scope.user.name);
            clientData.append('lastname', $scope.user.lastname);
            clientData.append('email', $scope.user.email);
            clientData.append('phone', $scope.user.phone);
            if ($scope.user.password != $scope.user.passwordConfirmation) 
            {
                clientData.append('password', $scope.user.password);
            }
            else
            {
                clientData.append('password', 0); 
            }
            clientData.append('userId', $scope.user.userId);
            $http({
                method: 'POST',
                url: url_updateUserData,
                data: clientData,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }) 
            .success(function (data) {
                Flash.create('success', data, 'customAlert');
                $http.get('/ajax/professors').success(function (data) {
                    $scope.list = data;
                    $scope.currentPage = 1; //current page
                    $scope.entryLimit = 5; //max no of items to display in a page
                    $scope.filteredItems = $scope.list.length; //Initially for no filter
                    $scope.totalItems = $scope.list.length;
                });
            })
            .error(function (data) {
                console.log(data);
            });
        }
    }

    $scope.processAddProfessor = function() {
        if (angular.isUndefined($scope.name)) 
        {
            Flash.create('danger', "Le nom de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.lastname)) 
        {
            Flash.create('danger', "Le prénom de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.email)) 
        {
            Flash.create('danger', "L'email de l'enseignant est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.phone)) 
        {
            Flash.create('danger', "Le numéro de téléphone est obligatoire", 'customAlert');
        }
        else if (angular.isUndefined($scope.password) || angular.isUndefined($scope.passwordConfirmation)) 
        {
            Flash.create('danger', "Le mot de passe est obligatoire", 'customAlert');
        }
        else if ($scope.password != $scope.passwordConfirmation) 
        {
            Flash.create('danger', "Le mot de passe et sa confirmation doivent être identiques", 'customAlert');
        }  
        else
        {
            var clientData = new FormData(); 
            clientData.append('name', $scope.name);
            clientData.append('lastname', $scope.lastname);
            clientData.append('email', $scope.email);
            clientData.append('phone', $scope.phone);
            clientData.append('password', $scope.password);
            clientData.append('role', 'ENSEIGNANT');
            $http({
                method: 'POST',
                url: url_addUserData,
                data: clientData,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }) 
            .success(function (data) {
                Flash.create('success', data, 'customAlert');
                $http.get('/ajax/professors').success(function (data) {
                    $scope.list = data;
                    $scope.currentPage = 1; //current page
                    $scope.entryLimit = 5; //max no of items to display in a page
                    $scope.filteredItems = $scope.list.length; //Initially for no filter
                    $scope.totalItems = $scope.list.length;
                });
            })
            .error(function (data) {
                console.log(data);
            });
        }
    }
    // $scope.deleteUser = function (userId) {
    //     var url_delete_user = Routing.generate('platin_delete_user', {userId: userId});
    //     $http.get(url_delete_user)
    //         .success(function (data) {
    //             $http.get('/ajax/professors').success(function (data) {
    //                 $scope.list = data;
    //                 $scope.currentPage = 1; //current page
    //                 $scope.entryLimit = 5; //max no of items to display in a page
    //                 $scope.filteredItems = $scope.list.length; //Initially for no filter
    //                 $scope.totalItems = $scope.list.length;
    //             });
    //             Flash.create('success', "L'enseignant a été supprimé avec succès", 'customAlert');
    //         })
    //         .error(function (data) {
    //             console.log(data);
    //         });
    // }
}
]);