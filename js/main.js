/**************
	MAIN
***************/
var inputLanguages, apis, results = [];
var inputValues;

var popularTech = ['css3','javascript','html','html5','ruby',
				   'objective-c','scala','perl','haskell','php',
				   'asp','clojure','sass','jquery','git',
				   'subversion','backbone','ember','angular',
				   'node.js','node','bootstrap','codeigniter',
				   'laravel','joomla','cakephp','ruby on rails',
				   'yii','zend','django','wordpress','drupal'];
				   
var apis = ['indeed','career-builder'];

var mobile = ((navigator.userAgent.match(/iPhone/i)) || 
			  (navigator.userAgent.match(/iPod/i))) 
			  ? true : false;

/**************
	ON LOAD
***************/
$(function() {
	
	// Warning class for blank fields
	dynamicFieldCheck();
		
	// Ajax Post on Form Submission
	$("#submit").click(function() {
		if(!checkForBlank()){
			$('.winner, .metrics-0, .metrics-1').fadeOut(100);
			$('.chart-wrapper').fadeOut(100);
			$('.loader').fadeIn(100);
			getInputLanguages();
			createInputObject();
			//console.log(inputValues);
			for(var i=0; i<apis.length; i++){
				inputValues.apiCount = i;
				$.ajax({
					type: "POST",
					url: "scraper.php",
					data: {data: JSON.stringify(inputValues)},
					success: function(data) {
						data = jQuery.parseJSON(data);
						console.log(data);
						createChart(data);
					}
				});	
			}
		}
		return false;
	});
	
	// Add new technology input field
  	$('.add').click(function() {
		var lastInputId = parseInt($( ".input-line:last-child input" ).attr( "data-input-id" ));
		$( ".input-line:last-child" ).after( inputTemplate( lastInputId+1 ));
		dynamicFieldCheck();
		return false;
	});
	
	// Delete last technology input field
	$('.delete').click(function() {
  		var lastInputId = parseInt($( ".input-line:last-child input" ).attr( "data-input-id" ));
  		if (lastInputId>2){
	  		$( "[data-input-id="+lastInputId+"]" ).parent().remove();
  		}
  		return false;
  	});
  	
  	/* Responsive Canvas */
	var c = $('.canvas-chart');
    var ct = c.get(0).getContext('2d');
    var container = $(c).parent().parent();

    $(window).resize( respondCanvas );

    function respondCanvas(){
	    if (mobile){
		    c.attr('width', $(container).width()/2 );
			c.attr('height', $(container).height()/2 -29);
	    } else {
		    c.attr('width', $('.left').width() );
			c.attr('height', $('.left').height() - 63);
	    }
    }
    respondCanvas();
  	
});

/****************
	FUNCTIONS	
*****************/
function dynamicFieldCheck(){
	$('#compare-form input').blur(function() {	
		checkForBlank();
	});	
}

function checkForBlank() {
	var error = 0;
	$( "#compare-form input:text" ).each(function(index) {
		if($(this).val()==""){
			$(this).parent().addClass('warning');
			error = 1;
		} else {
			$(this).parent().removeClass('warning');
		}
	});
	return error;
} 

function createInputObject(){
	inputValues = {
          city:$("#city").val(),
          state: $("#state").val(),
          salary: 0,
          radius: 20,
          languages: inputLanguages,
          apiType: apis,
          apiCount: 0
    };
}

function createChart( apiData ){
	
	var technologies = apiData[0];
	var numberOfJobs = apiData[1];
	var timeToRun = apiData[2];
	var chartCount = apiData[3];
	
	$('.loader[data-load="'+chartCount+'"]').fadeOut(100);
	
	var data = {
		labels : [],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : []
			}
		]
	};
	data.labels = technologies;
	data.datasets[0].data = numberOfJobs;

	var options = {
	    scaleBeginAtZero: true
	};
	var $chart = $("#chart-"+parseInt(chartCount));
	var $chartWrapper = $('.left');
	$chart.attr('width', $chartWrapper.width() + 'px');
	$chart.attr('height', $chartWrapper.height() - 65 + 'px');
	var ctx = $chart.get(0).getContext("2d");
	//(new Chart(ctx));
	new Chart(ctx).Bar(data, options);
	addMetrics( technologies, numberOfJobs, timeToRun, chartCount );
}


function addMetrics( technologies, numberOfJobs, timeToRun, chartCount ){
	
	timeToRun = Math.round((timeToRun + 0.00001) * 1000) / 1000;
	$('.metrics-'+chartCount+' ul').html('');
	
	for(var i=0; i<technologies.length; i++){
		console.log(technologies[i]);
		txt = '<li>' + technologies[i] + ': <strong>' +  numberOfJobs[i] + '</strong></li>';
		$('.metrics-'+chartCount+' ul').append(txt);
	}	
	
	txt = '<li>It took: <span>'+ timeToRun + '</span> seconds to run.</li>'
	$('.metrics-'+chartCount+' ul').append(txt);
	$('.chart-wrapper[data-chart-number='+chartCount+']').fadeIn(100);	
	
	winnerIsSection( numberOfJobs, chartCount, technologies );
}

function winnerIsSection( numberOfJobs, chartCount, technologies ){
	
	highestTechNum = Math.max.apply(Math, numberOfJobs); // highest number of jobs
	// index of highest technology in array
	highestTechIndex = numberOfJobs.indexOf(Math.max.apply(Math, numberOfJobs)); 
	highestTechName = technologies[highestTechIndex];
	
	results[chartCount] = [ highestTechName, highestTechNum ];
	var maxJob = maxJobIndex =[];
	var maxJobIndex;
	
	if(chartCount == apis.length-1){
		$('.winner').html('<h3>And the winner is:</h3>');
		if(results[0][0]!=results[1][0]){	
		
			txt  = '<h2>'+results[0][0]+'</h2><p> for Indeed.com with <span>';
			txt += results[0][1]+'</span> jobs and</p>';
			$('.winner').append(txt);
			
			txt  = '<h2>'+results[1][0]+'</h2><p> for Careerbuilder.com with ';
			txt += '<span>'+results[1][1]+'</span> jobs</p>';
			$('.winner').append(txt);
			
		} else {
		
			txt  = '<h2>'+results[chartCount][0]+'</h2>';
			txt += '<p>With <span>'+results[0][1];
			txt += '</span>(Indeed) Jobs and <span>'+results[1][1];
			txt += '</span>(CareerBuilder) Jobs';
			$('.winner').append(txt);
			
		}
		
		txt = ' posted near <span>'+inputValues.city + ", " + inputValues.state+'</span></p>';
		$('.winner').append(txt);
		$('.winner, .metrics-1, .metrics-0').fadeIn(100);
	}
}

function inputTemplate (id) {
	randomTech = popularTech[Math.floor(Math.random()*popularTech.length)];
	txt  = '<div class="input-line"><input type="text" name="language';
	txt += id+'" id="language'+id+'" data-input-id="'+id+'" value="'+randomTech;
	txt += '" autocomplete="off"></div>';
	return txt;
}

function getInputLanguages(){
	inputLanguages = $('.input-line input').map(function () { 
		return $(this).val(); 
	}).get();
}