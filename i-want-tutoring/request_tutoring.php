
<div class="row">

<div class="col-md-8">
    <div class="card">

        <div class="header" style="margin-left:20px; margin-top:10px;">

            <!--this is the HTML for the calendar. The calendar is where the id = calendar-->
            <h2 class="title"></h2>
            <h4 class="category"></h4>
        </div>
        <div class="" style="padding-left:15px;">
            <div class="container">
                <div id="calendar"></div>

            </div>
        </div>
    </div>
</div>



	<!--This is the javascript for the calendar-->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
	<script>
		$( document ).ready( function () {
			var calendar = $( '#calendar' ).fullCalendar( {
				editable: false,
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek'
				},
				events: 'tutorload.php',
				//select a particular cell, dragging events and stuff
				//loads the things from the database
				selectable: false,
				selectHelper: false,
				select: function ( start, end, allDay ) {
					var title = prompt( "Enter Event Title" );
					if ( title ) {
						var start = $.fullCalendar.formatDate( start, "Y-MM-DD HH:mm:ss" );
						var end = $.fullCalendar.formatDate( end, "Y-MM-DD HH:mm:ss" );
						$.ajax( {
							url: "insert.php",
							//This inserts
							type: "POST",
							data: {
								title: title,
								start: start,
								end: end
							},
							success: function () {
								calendar.fullCalendar( 'refetchEvents' );
								alert( "Added Successfully" );
							}
						} )
					}
				},
				//You are allowed to edit the table....
				editable: false,
				eventResize: function ( event ) {
					var start = $.fullCalendar.formatDate( event.start, "Y-MM-DD HH:mm:ss" );
					var end = $.fullCalendar.formatDate( event.end, "Y-MM-DD HH:mm:ss" );
					var title = event.title;
					var id = event.id;
					$.ajax( {
						url: "update.php",
						type: "POST",
						data: {
							title: title,
							start: start,
							end: end,
							id: id
						},
						success: function () {
							calendar.fullCalendar( 'refetchEvents' );
							alert( 'Event Update' );
						}
					} )
				},

				eventDrop: function ( event ) {
					var start = $.fullCalendar.formatDate( event.start, "Y-MM-DD HH:mm:ss" );
					var end = $.fullCalendar.formatDate( event.end, "Y-MM-DD HH:mm:ss" );
					var title = event.title;
					var id = event.id;
					$.ajax( {
						url: "update.php",
						type: "POST",
						data: {
							title: title,
							start: start,
							end: end,
							id: id
						},
						success: function () {
							calendar.fullCalendar( 'refetchEvents' );
							alert( "Event Updated" );
						}
					} );
				},

				eventClick: function ( event ) {
					if ( confirm( "Schedule a tutor for this date?" ) ) {
						var id = event.id;
						var title = event.title;
						var studentid = event.studentid;
						$.ajax( {
							url: "dateidtophp.php",
							type: "POST",
							data: {
								id: id,
								title: title,
								studentid: studentid
							},
							success: function () {
								calendar.fullCalendar( 'refetchEvents' );
								//        alert("Parameters Set");
								document.location.reload( true )
							}
						} )
					}
				},
			} );
		} );
	</script>



	</body>
</html>

