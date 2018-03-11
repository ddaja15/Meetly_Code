var currentNumberOfSections, totalNumberOfSections, numberOfQuestions;

var lastLat, lastLng;

var map;

$(document).ready(function () {

    var myLatlng = new google.maps.LatLng(41.3275, 19.8187);

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: myLatlng,
        scrollwheel: true, //we disable de scroll over the map, it is a really annoing when you scroll through page
        styles: [{
            "featureType": "water",
            "stylers": [{"saturation": 43}, {"lightness": -11}, {"hue": "#0088ff"}]
        }, {
            "featureType": "road",
            "elementType": "geometry.fill",
            "stylers": [{"hue": "#ff0000"}, {"saturation": -100}, {"lightness": 99}]
        }, {
            "featureType": "road",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#808080"}, {"lightness": 54}]
        }, {
            "featureType": "landscape.man_made",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#ece2d9"}]
        }, {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#ccdca1"}]
        }, {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#767676"}]
        }, {
            "featureType": "road",
            "elementType": "labels.text.stroke",
            "stylers": [{"color": "#ffffff"}]
        }, {"featureType": "poi", "stylers": [{"visibility": "off"}]}, {
            "featureType": "landscape.natural",
            "elementType": "geometry.fill",
            "stylers": [{"visibility": "on"}, {"color": "#b8cb93"}]
        }, {
            "featureType": "poi.park",
            "stylers": [{"visibility": "on"}]
        }, {
            "featureType": "poi.sports_complex",
            "stylers": [{"visibility": "on"}]
        }, {
            "featureType": "poi.medical",
            "stylers": [{"visibility": "on"}]
        }, {"featureType": "poi.business", "stylers": [{"visibility": "simplified"}]}]

    });



    var geocoder = new google.maps.Geocoder();

    // Geocoding
    $('.search-btn').click(function () {
        geocodeAddress(geocoder, map);
    });

    $('.search-btn-modal').click(function () {

        $('.finalize-btn').css('display', 'block');
        $.notify({
            icon: 'pe-7s-marker',
            message: "Drag the marker around to specify the location correctly and press finalize!"

        }, {
            type: 'danger',
            timer: 5000
        });
        geocodeAddressModal(geocoder, map);

    });

    // Get final values and send to controller
    $('.finalize-btn').click(function () {

        var jobTitle = document.getElementById('job-title').value;
        var jobEmployee = document.getElementById('job-employee').value;
        var jobDeadline = document.getElementById('job-deadline').value;
        var jobPriority = document.getElementById('job-priority').value;
        var jobReward = document.getElementById('job-reward').value;
        var jobDifficulty = document.getElementById('job-difficulty').value;
        var jobDesc = document.getElementById('job-desc').value;

        // alert(jobTitle + " " + jobDeadline + " " + jobEmployee + " "  + jobPriority + " " + jobReward + " " + jobDifficulty + " " + jobDesc);

        var jsonForm = getFormJson(false);

        $.ajax({
            type: "POST",
            url: '/add/job',
            data: {
                'job-title': jobTitle,
                'job-employee': jobEmployee,
                'job-deadline': jobDeadline,
                'job-priority': jobPriority,
                'job-reward': jobReward,
                'job-difficulty': jobDifficulty,
                'job-desc': jobDesc,
                'json-form': jsonForm,
                'latitude': lastLat,
                'longitude': lastLng
            },
            success: function (msg) {
                location.reload();
            }
        });


    });

    // $('li > a').click(function() {
    //     $('li').removeClass();
    //     $('.map-active').addClass('active');
    // });


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        //update progress
        var step = $(e.target).data('step');
        var percent = (parseInt(step) / 3) * 100;


        if (parseInt(step) === 1) {

            color = 'rgba(127, 255, 127, 0.4)';

        } else if (parseInt(step) === 2) {
            color = 'rgba(127, 255, 127, 0.8)';

        } else {
            color = 'rgba(127, 255, 127, 1)';
        }

        $('.progress-bar').css({width: percent + '%', backgroundColor: color});
        $('.progress-steps').text("Step " + step + " of 3");

        //e.relatedTarget // previous tab
    });

    $('.first').click(function () {

        $('#myWizard a:first').tab('show')

    });


    currentNumberOfSections = 1;
    totalNumberOfSections = 1;
    numberOfQuestions = 0;
    $("#mainContainer").append("<div class='sectionDiv' id='section" + currentNumberOfSections + "'><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:10px;color: rgba(0,0,0,0.6)' id='section" + currentNumberOfSections + "' onclick='deleteSection(this.id)'>delete</i><span class='mySpan' style='position:absolute;top: 15px;right: 15px;font-size: 16px;font-weight: bold;color: rgba(0,0,0,0.6)'>" + currentNumberOfSections + "/" + totalNumberOfSections + "</span><input type='text' class='headerInputs' placeholder='Section Name' id='sectionName" + currentNumberOfSections + "'/><br><br><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:40px;color: rgba(0,0,0,0.6)' id='sectionAddQuestion" + currentNumberOfSections + "' onclick='clickedAddQuestion(this.id)'>add_circle</i></div>");
});

//When first time clicked on add first section
/*
 On click addFirstSection do the following:
 Remove the container make invisible.
 Add dynamically a top toolbar that has buttons: Add new section / Finish form on the main container
 Add the div section that at first takes 100% of space. It generally will have width of 100/current_number_of_sections.
 Each div section will have class that will dynamically change widths, and id.
 Extra: Getting parent : onclick='duplicateSection($(this).parent().get(0).id)
 */
$(document).on("click", "#addFirstSection", function () {
    currentNumberOfSections++;
    totalNumberOfSections++;
    // $("#toolbarContainer").append("<div id='addAnotherSection'>Add new section</div>");
    $("#firstContainer").css("display", "none");
    $("#mainContainer").append("<div class='sectionDiv' id='section" + currentNumberOfSections + "'><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:10px;color: rgba(0,0,0,0.6)' id='section" + currentNumberOfSections + "' onclick='deleteSection(this.id)'>delete</i><span class='mySpan' style='position:absolute;top: 15px;right: 15px;font-size: 16px;font-weight: bold;color: rgba(0,0,0,0.6)'>" + currentNumberOfSections + "/" + totalNumberOfSections + "</span><input type='text' class='headerInputs' placeholder='Section Name' id='sectionName" + currentNumberOfSections + "'/><br><br><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:40px;color: rgba(0,0,0,0.6)' id='sectionAddQuestion" + currentNumberOfSections + "' onclick='clickedAddQuestion(this.id)'>add_circle</i></div>");
});


/*
 On click addAnotherSection do the following:
 Add the div section that at first takes 100% of space. It generally will have width of 100/current_number_of_sections
 */

$(document).on("click", "#addAnotherSection", function () {
    currentNumberOfSections++;
    totalNumberOfSections++;
    $("#mainContainer").append("<center><div class='sectionDiv' id='section" + currentNumberOfSections + "'><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:10px;color: rgba(0,0,0,0.6)' id='section" + currentNumberOfSections + "' onclick='deleteSection(this.id)'>delete</i><span class='mySpan' style='position:absolute;top: 15px;right: 15px;font-size: 16px;font-weight: bold;color: rgba(0,0,0,0.6)'>" + currentNumberOfSections + "/" + totalNumberOfSections + "</span><input type='text' class='headerInputs' placeholder='Section Name' id='sectionName" + currentNumberOfSections + "'/><br><br><i class='material-icons' style='cursor:pointer;position: absolute;top:10px;left:40px;color: rgba(0,0,0,0.6)' id='sectionAddQuestion" + currentNumberOfSections + "' onclick='clickedAddQuestion(this.id)'>add_circle</i></div></center>");

    $('.mySpan').each(function (i, obj) {
        var currentIndex = i + 1;
        obj.innerHTML = currentIndex + "/" + totalNumberOfSections;
        var cl = obj.cloneNode(true);
        var parent = obj.parentNode;
        parent.removeChild(obj);
        parent.appendChild(cl);
    });

});

//Generate the JSON form and display it in place

$(document).on("click", "#finishForm", function () {
    var jsonForm = getFormJson(false);
    $(".flex-container").css("display", "none");
    $("#output").append(jsonForm);
});

//Generate the JSON form and save as a predefined form
$(document).on("click", "#saveForm", function () {
    var jsonForm = getFormJson(false);
    //Save in DB
});

//Append the question format on specific section.
function clickedAddQuestion(buttonID) {
    numberOfQuestions++;

    //Get section number
    var sectionNumber = buttonID[buttonID.length - 1];

    //Append a new input for question in the section number
    $("#section" + sectionNumber).append("<div style='margin-top: 15px;' class='question" + sectionNumber + "'><input class='form-control' type='text' style='width: 70%; display: inline; ' placeholder=' Question Content..' /> <select name='select' style='width: 20%; ;padding: 5px;border: none;font-family: Raleway'><option value='text' selected>Text</option><option value='image'>Picture</option><option value='scale'>Scale</option><option value='numeric'>Numeric</option></select><i class='material-icons' onclick='$(this).parent().get(0).remove();' style='cursor:pointer;width: 5%; position: relative; top:10px; font-size: 30px;color: red;left:15px'>clear</i></div>");
}


//Remove the specific section and fix all indexes of other sections.
function deleteSection(sectionID) {
    currentNumberOfSections--;
    totalNumberOfSections--;

    $("#" + sectionID + "").remove();

    $('.mySpan').each(function (i, obj) {
        var currentIndex = i + 1;
        obj.innerHTML = currentIndex + "/" + totalNumberOfSections;
        obj.setAttribute("id", "section" + currentIndex);
        var cl = obj.cloneNode(true);
        var parent = obj.parentNode;
        parent.removeChild(obj);
        parent.appendChild(cl);
    });
}

/*
 Pre-generate the form and save the jsonResponse in your DB as a predefined section.
 */

function saveSection(sectionID) {
    var responseJson = getFormJson(true, sectionID);
    console.log(responseJson);
}

/*
 Not used for now
 */
function duplicateSection(sectionID) {
    currentNumberOfSections++;
    totalNumberOfSections++;
    $("#mainContainer").append("<center><div class='sectionDiv' id='section" + currentNumberOfSections + "'>" + $("#" + sectionID).html() + "</div></center>");
}


/*
 From what we can see the format in terms of maps should be :
 Out of map : Start json array [
 For each section :
 Start json object {
 'input_of_sectionNameX' :
 Start json array for questions
 [
 For each question inside
 'current question counter' : [
 first it -'content':'value_of_current_input'
 second it-'type':'value_of_current_input'
 ],
 End of section },
 End of everything ]
 */

function getFormJson(section, sectionID) {

    var saveWhat = section ? "#" + sectionID : ".sectionDiv";

    var jsonForm = "[";

    $(saveWhat).map(function () {

        var sectionNumber = this.id[this.id.length - 1];
        var sectionName = $("#sectionName" + sectionNumber).val();

        jsonForm += "{ '" + sectionName + "' : [";

        var currentQuestion = 0;

        $('.question' + sectionNumber).map(function () {
            var questionName = "question" + currentQuestion;
            var content = $(this).children()[0].value;

            var select = $(this).find("select").find("option:selected").val();
            console.log(select);

            jsonForm += "'" + questionName + "' : ['content':'" + content + "','type':'" + select + "'],";

            currentQuestion++;
        });
        currentQuestion = 0;

        jsonForm += "] } ,";

    });

    jsonForm += "]";
    return jsonForm;
}


// Geocoding
function geocodeAddress(geocoder, resultsMap) {
    var address = document.getElementById('search-text').value;
    geocoder.geocode({'address': address}, function (results, status) {
        if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location
            });
        } else {
            alert('Error: ' + status);
        }
    });
}


// Geocoding
function geocodeAddressModal(geocoder, resultsMap) {

    var address = document.getElementById('search-text-modal').value;
    geocoder.geocode({'address': address}, function (results, status) {
        if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var myMarker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location,
                draggable: true,
            });

            lastLat = results[0].geometry.location.lat();
            lastLng = results[0].geometry.location.lng();

            google.maps.event.addListener(myMarker, 'dragend', function (evt) {
                lastLat = evt.latLng.lat();
                lastLng = evt.latLng.lng();
            });

        } else {
            alert('Error: ' + status);
        }
    });
}


function getMap(){
    return this.map;
}


// Test

