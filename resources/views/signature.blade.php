<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signature</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        canvas {
            border: 2px solid black;
            background-color: white;
            touch-action: none; /* Prevents scrolling while signing */
        }
        button {
            margin-top: 10px;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Draw Your Signature</h2>
    <canvas id="signatureCanvas"></canvas>
    <br>
    <button id="clear">Clear</button>
    <button id="save">Save</button>

    <script>
        let canvas = document.getElementById("signatureCanvas");
        let ctx = canvas.getContext("2d");
        
        // Set proper canvas size
        function resizeCanvas() {
            canvas.width = Math.min(500, window.innerWidth - 40); // Adjust width responsively
            canvas.height = 200;
        }
        resizeCanvas();

        let drawing = false;
        let lastPos = null; // Store last position to continue smoothly

        // Configure smoother strokes
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        ctx.strokeStyle = "black";

        function getPosition(event) {
            let rect = canvas.getBoundingClientRect();
            if (event.touches) {
                let touch = event.touches[0];
                return { x: touch.clientX - rect.left, y: touch.clientY - rect.top };
            }
            return { x: event.clientX - rect.left, y: event.clientY - rect.top };
        }

        function startDrawing(event) {
            event.preventDefault();
            drawing = true;
            lastPos = getPosition(event); // Store where we start
        }

        function draw(event) {
            if (!drawing) return;
            event.preventDefault();
            let pos = getPosition(event);

            ctx.beginPath();
            ctx.moveTo(lastPos.x, lastPos.y); // Continue from the last point
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            
            lastPos = pos; // Update last position
        }

        function stopDrawing(event) {
            event.preventDefault();
            drawing = false;
        }

        // Mouse Events
        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseleave", stopDrawing);

        // Touch Events
        canvas.addEventListener("touchstart", startDrawing);
        canvas.addEventListener("touchmove", draw);
        canvas.addEventListener("touchend", stopDrawing);
        canvas.addEventListener("touchcancel", stopDrawing);

        document.getElementById("clear").addEventListener("click", function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            lastPos = null; // Reset position when clearing
        });

        document.getElementById("save").addEventListener("click", function () {
            let signatureData = canvas.toDataURL("image/png");

            fetch("{{ route('signature.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ signature: signatureData })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        });

        // Resize canvas on window resize
        window.addEventListener("resize", resizeCanvas);
    </script>
</body>
</html>
