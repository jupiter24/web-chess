<!DOCTYPE html>
<html>
	<head>
		<title>WebChess</title>
		<meta charset="UTF8">
		<link type="text/css" rel="stylesheet" href="chessboardjs/css/chessboard-0.3.0.css">
		<link type="text/css" rel="stylesheet" href="css/main.css">
		<script src="jquery/jquery-2.1.3.min.js"></script>
		<script src="jquery-ui/jquery-ui.min.js"></script>
		<script src="chessboardjs/js/chessboard-0.3.0.js"></script>
		<script src="chessjs/chess.js"></script>
	</head>
	<body>
		<div id="mainNav">
			<ul>
				<li id="mainNav-openDatabaseButton">Open Database</li>
				<li>Create new database</li>
				<li>New game</li>
				<li>Save game</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div id="modeNav">
			<ul>
				<li>Mode 1</li>
				<li>Mode 2</li>
				<li>Mode 3</li>
			</ul>
		</div>
		<div id="chessboard" style="width: 400px;"></div>
		<input type="button" id="flipOrientationButton" value="Flip orientation" />
		
		<p>Status: <span id="status"></span></p>
		<p>FEN: <span id="fen"></span></p>
		<p>PGN: <span id="pgn"></span></p>
		
		<script>
			var board,
			  game = new Chess(),
			  statusEl = $('#status'),
			  fenEl = $('#fen'),
			  pgnEl = $('#pgn');

			// do not pick up pieces if the game is over
			// only pick up pieces for the side to move
			var onDragStart = function(source, piece, position, orientation) {
			  if (game.game_over() === true ||
				  (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
				  (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
				return false;
			  }
			};

			var onDrop = function(source, target) {
			  // see if the move is legal
			  var move = game.move({
				from: source,
				to: target,
				promotion: 'q' // NOTE: always promote to a queen for example simplicity
			  });

			  // illegal move
			  if (move === null) return 'snapback';

			  updateStatus();
			};

			// update the board position after the piece snap 
			// for castling, en passant, pawn promotion
			var onSnapEnd = function() {
			  board.position(game.fen());
			};

			var updateStatus = function() {
			  var status = '';

			  var moveColor = 'White';
			  if (game.turn() === 'b') {
				moveColor = 'Black';
			  }

			  // checkmate?
			  if (game.in_checkmate() === true) {
				status = 'Game over, ' + moveColor + ' is in checkmate.';
			  }

			  // draw?
			  else if (game.in_draw() === true) {
				status = 'Game over, drawn position';
			  }

			  // game still on
			  else {
				status = moveColor + ' to move';

				// check?
				if (game.in_check() === true) {
				  status += ', ' + moveColor + ' is in check';
				}
			  }

			  statusEl.html(status);
			  fenEl.html(game.fen());
			  pgnEl.html(game.pgn());
			};

			var cfg = {
			  draggable: true,
			  position: 'start',
			  onDragStart: onDragStart,
			  onDrop: onDrop,
			  onSnapEnd: onSnapEnd
			};
			board = new ChessBoard('chessboard', cfg);

			updateStatus();
		</script>
		
		<div class="dialog" id="openDatabaseDialog" style="width: 500px;">
			<div class="closeDialog"></div>
			List of Databases:
			<ul id="openDatabaseDialog-databaseList">
				
			</ul>
		</div>
		<script src="js/ui.js"></script>
	</body>
</html>