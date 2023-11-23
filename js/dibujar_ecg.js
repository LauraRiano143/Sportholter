function adjustCanvasAndDraw() {
    var canvas = document.getElementById('myCanvas');
    var canvasContainer = document.querySelector('.canvas-container');
    canvas.width = canvasContainer.clientWidth;
    canvas.height = canvasContainer.clientHeight;
}

function getECGData(canvas) {
    var data = [];
    var canvasHeight = canvas.height;
    var canvasWidth = canvas.width;

    var scaleY = canvasHeight / 4;
    var cycles = 2;
    var samplesPerCycle = canvasWidth / cycles;

    for (var x = 0; x < canvasWidth; x++) {
        var cycle = Math.floor(x / samplesPerCycle);
        var time = (x / samplesPerCycle) - cycle;

        var y = canvasHeight / 2;

        if (time >= 0 && time < 0.1) {
            y += Math.sin(time * Math.PI / 0.1) * scaleY;
        } else if (time >= 0.1 && time < 0.2) {
            y += Math.sin((time - 0.1) * Math.PI / 0.1) * scaleY * 2 * (time < 0.15 ? -1 : 1);
        } else if (time >= 0.2 && time < 0.3) {
            y += Math.sin((time - 0.2) * Math.PI / 0.1) * scaleY / 2;
        }

        data.push({ x: x, y: y });
    }

    return data;
}

function drawECG() {
    var canvas = document.getElementById('myCanvas');
    if (canvas.getContext) {
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 2;

        var ecgData = getECGData(canvas);
        ctx.moveTo(ecgData[0].x, ecgData[0].y);
        ecgData.forEach(point => {
            ctx.lineTo(point.x, point.y);
        });

        ctx.stroke();
    }
}

window.addEventListener('resize', adjustCanvasAndDraw);

document.addEventListener('DOMContentLoaded', function() {
    var btnStart = document.querySelector('button[type="submit"]');
    btnStart.addEventListener('click', function(event) {
        event.preventDefault();
        adjustCanvasAndDraw();
        drawECG();
    });

    document.addEventListener('DOMContentLoaded', adjustCanvas);
});
