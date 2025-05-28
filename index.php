<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bangunan - Loading</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }
        
        .loading-container {
            width: 400px;
            height: 300px;
            position: relative;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .construction-site {
            width: 300px;
            height: 180px;
            margin: 0 auto;
            position: relative;
            background-color: #f9f9f9;
            border: 2px dashed #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .building {
            position: absolute;
            bottom: 0;
            left: 50px;
            width: 200px;
            height: 0;
            background-color: #3498db;
            transition: height 0.5s ease;
        }
        
        .floor {
            position: absolute;
            width: 100%;
            height: 20px;
            background-color: #2980b9;
            border-bottom: 2px solid #fff;
        }
        
        .crane {
            position: absolute;
            right: 30px;
            bottom: 0;
            width: 40px;
            height: 180px;
        }
        
        .crane-tower {
            position: absolute;
            width: 10px;
            height: 100%;
            background-color: #7f8c8d;
            left: 15px;
        }
        
        .crane-arm {
            position: absolute;
            top: 20px;
            width: 80px;
            height: 5px;
            background-color: #7f8c8d;
            transform-origin: left center;
            animation: craneSwing 3s infinite ease-in-out;
        }
        
        .crane-hook {
            position: absolute;
            width: 5px;
            height: 30px;
            background-color: #e74c3c;
            left: 75px;
            animation: hookMove 3s infinite ease-in-out;
        }
        
        .brick {
            position: absolute;
            width: 20px;
            height: 10px;
            background-color: #e74c3c;
            animation: brickMove 3s infinite;
        }
        
        .progress-text {
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            width: 0;
            background-color: #2ecc71;
            border-radius: 5px;
            transition: width 0.3s ease;
        }
        
        @keyframes craneSwing {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }
        
        @keyframes hookMove {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(20px); }
        }
        
        @keyframes brickMove {
            0% { 
                transform: translate(0, 0);
                opacity: 0;
            }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { 
                transform: translate(-100px, 100px);
                opacity: 0;
            }
        }
        
        .worker {
            position: absolute;
            width: 15px;
            height: 25px;
            background-color: #f39c12;
            border-radius: 3px;
            bottom: 0;
            animation: workerMove 5s infinite;
        }
        
        .worker:after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f1c40f;
            border-radius: 50%;
            top: -8px;
            left: 2.5px;
        }
        
        @keyframes workerMove {
            0% { left: 10px; }
            50% { left: 50px; }
            100% { left: 10px; }
        }
    </style>
</head>
<body>
    <div class="loading-container">
        <div class="logo">TOKO BANGUNAN MUTIARA</div>
        
        <div class="construction-site">
            <!-- Building that grows as loading progresses -->
            <div class="building" id="building">
                <div class="floor" style="bottom: 0;"></div>
                <div class="floor" style="bottom: 20px;"></div>
                <div class="floor" style="bottom: 40px;"></div>
                <div class="floor" style="bottom: 60px;"></div>
                <div class="floor" style="bottom: 80px;"></div>
            </div>
            
            <!-- Construction crane -->
            <div class="crane">
                <div class="crane-tower"></div>
                <div class="crane-arm"></div>
                <div class="crane-hook"></div>
            </div>
            
            <!-- Bricks being lifted -->
            <div class="brick" style="top: 30px; left: 120px; animation-delay: 0.5s;"></div>
            <div class="brick" style="top: 50px; left: 140px; animation-delay: 1s;"></div>
            <div class="brick" style="top: 70px; left: 160px; animation-delay: 1.5s;"></div>
            
            <!-- Construction workers -->
            <div class="worker" style="left: 10px; animation-delay: 0.2s;"></div>
            <div class="worker" style="left: 30px; animation-delay: 0.4s;"></div>
        </div>
        
        <div class="progress-text">Menyiapkan bahan bangunan...</div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>

    <script>
        // Simulate loading progress
        let progress = 0;
        const progressFill = document.getElementById('progressFill');
        const building = document.getElementById('building');
        const messages = [
            "Menyiapkan bahan bangunan...",
            "Memasang fondasi...",
            "Membangun dinding...",
            "Memasang atap...",
            "Hampir selesai..."
        ];
        const progressText = document.querySelector('.progress-text');
        
        function updateProgress() {
            progress += Math.random() * 5;
            if (progress > 100) progress = 100;
            
            progressFill.style.width = `${progress}%`;
            building.style.height = `${(progress / 100) * 150}px`;
            
            // Update progress message
            const messageIndex = Math.min(Math.floor(progress / 20), messages.length - 1);
            progressText.textContent = messages[messageIndex];
            
            if (progress < 100) {
                setTimeout(updateProgress, 300);
            } else {
                // Redirect when complete
                setTimeout(() => {
                    window.location.href = "auth/login.php";
                }, 300);
            }
        }
        
        // Start the progress
        setTimeout(updateProgress, 500);
        
        // Add some random bricks flying in
        setInterval(() => {
            if (progress < 100) {
                const brick = document.createElement('div');
                brick.className = 'brick';
                brick.style.left = `${Math.random() * 100 + 180}px`;
                brick.style.top = `${Math.random() * 50}px`;
                brick.style.animationDelay = `${Math.random() * 2}s`;
                document.querySelector('.construction-site').appendChild(brick);
                
                // Remove brick after animation
                setTimeout(() => {
                    brick.remove();
                }, 3000);
            }
        }, 800);
    </script>
</body>
</html>