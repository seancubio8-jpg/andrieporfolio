<?php
/**
 * Arduino Smart Touchless Water Dispenser - Interactive Hardware Live Demo
 * Created for Sean Adrei Cubio's BSIT Student Portfolio
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arduino Touchless Water Dispenser | IoT Hardware Live Demo</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --cyan-glow: rgba(6, 182, 212, 0.4);
            --dark-bg: #0b1120;
            --card-bg: #111c34;
            --card-border: #1e293b;
            --terminal-bg: #050b14;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-amber: #f59e0b;
            --lcd-bg: #0284c7;
            --radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Demo Bar */
        .demo-top-bar {
            background: #050a15;
            color: #94a3b8;
            padding: 10px 24px;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #1e293b;
        }
        .demo-top-bar a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s;
        }
        .demo-top-bar a:hover { color: #38bdf8; text-decoration: underline; }
        .demo-top-bar .author-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(6, 182, 212, 0.12);
            color: #38bdf8;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 12px;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        /* Header */
        .app-header {
            background: rgba(17, 28, 52, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #1e293b;
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .header-content {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0891b2, #0284c7);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 0 15px var(--cyan-glow);
        }
        .brand-text h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .brand-text span {
            font-size: 12px;
            color: var(--primary);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-audio-toggle {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-audio-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .btn-audio-toggle.active {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.4);
            color: #34d399;
        }

        /* Main Container */
        .main-container {
            max-width: 1300px;
            margin: 24px auto;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }

        /* Grid Layout */
        .iot-grid {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 992px) {
            .iot-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Card Styles */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0891b2, #3b82f6, #06b6d4);
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #1e293b;
        }
        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }
        .card-title i { color: var(--primary); }

        /* Hardware Rig Viewport */
        .dispenser-viewport {
            background: #070d18;
            border: 2px solid #1e293b;
            border-radius: 12px;
            height: 360px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: inset 0 0 40px rgba(0,0,0,0.8);
        }

        /* Nozzle Assembly */
        .nozzle-mount {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 10;
        }
        .dispenser-head {
            width: 140px;
            height: 34px;
            background: linear-gradient(180deg, #475569, #334155);
            border-radius: 0 0 10px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.5px;
            border: 1px solid #64748b;
            border-top: none;
            box-shadow: 0 6px 12px rgba(0,0,0,0.5);
        }
        .ultrasonic-sensor {
            background: #0284c7;
            padding: 4px 10px;
            border-radius: 6px;
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: -4px;
            border: 1px solid #38bdf8;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
        }
        .sensor-eye {
            width: 16px;
            height: 16px;
            background: #0f172a;
            border: 2px solid #94a3b8;
            border-radius: 50%;
            position: relative;
        }
        .sensor-eye::after {
            content: '';
            position: absolute;
            width: 6px;
            height: 6px;
            background: #38bdf8;
            border-radius: 50%;
            top: 3px;
            left: 3px;
        }
        .spout-pipe {
            width: 14px;
            height: 28px;
            background: linear-gradient(90deg, #94a3b8, #cbd5e1, #64748b);
            border-radius: 0 0 4px 4px;
            position: relative;
        }

        /* Sensor Waves Simulation */
        .sensor-waves {
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            pointer-events: none;
            z-index: 5;
        }
        .wave-ring {
            border: 2px dashed rgba(6, 182, 212, 0.4);
            border-radius: 50%;
            width: 60px;
            height: 20px;
            animation: pulseWave 1.4s infinite ease-out;
            opacity: 0;
        }
        .wave-ring:nth-child(2) { animation-delay: 0.4s; width: 90px; }
        .wave-ring:nth-child(3) { animation-delay: 0.8s; width: 120px; }

        @keyframes pulseWave {
            0% { transform: scale(0.6); opacity: 0.8; }
            100% { transform: scale(1.3); opacity: 0; }
        }

        /* Water Stream Animation */
        .water-stream {
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 0px;
            background: linear-gradient(180deg, #38bdf8, #0284c7, #06b6d4);
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.8);
            border-radius: 4px;
            z-index: 8;
            opacity: 0;
            transition: height 0.3s ease, opacity 0.2s ease;
        }
        .water-stream.pouring {
            height: 180px;
            opacity: 1;
            animation: flowJiggle 0.15s infinite alternate;
        }
        @keyframes flowJiggle {
            0% { width: 7px; filter: blur(0.2px); }
            100% { width: 9px; filter: blur(0.6px); }
        }

        /* Dispenser Tray Base */
        .dispenser-base {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 48px;
            background: linear-gradient(180deg, #1e293b, #0f172a);
            border-top: 2px solid #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .drip-grill {
            width: 220px;
            height: 16px;
            background: repeating-linear-gradient(90deg, #334155, #334155 8px, #0f172a 8px, #0f172a 16px);
            border-radius: 4px;
            border: 1px solid #475569;
        }

        /* Realistic Cup */
        .cup-container {
            position: absolute;
            bottom: 46px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 130px;
            z-index: 12;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s;
        }
        .cup-container.removed {
            transform: translateX(140px);
            opacity: 0.35;
        }

        .glass-cup {
            width: 100%;
            height: 100%;
            border-left: 3px solid rgba(255, 255, 255, 0.5);
            border-right: 3px solid rgba(255, 255, 255, 0.5);
            border-bottom: 5px solid rgba(255, 255, 255, 0.6);
            border-radius: 0 0 16px 16px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .cup-rim {
            width: 100%;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            margin-bottom: -3px;
        }
        .water-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 0%;
            background: linear-gradient(180deg, rgba(56, 189, 248, 0.8), rgba(2, 132, 199, 0.9));
            transition: height 0.2s ease;
            border-radius: 0 0 12px 12px;
        }
        .water-surface {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: #7dd3fc;
            border-radius: 50%;
            opacity: 0.9;
        }
        .water-level-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 11px;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
            font-family: 'Fira Code', monospace;
            pointer-events: none;
        }

        /* Distance Indicator Ruler Overlay */
        .distance-tag {
            position: absolute;
            top: 85px;
            right: 20px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #334155;
            padding: 8px 14px;
            border-radius: 8px;
            font-family: 'Fira Code', monospace;
            font-size: 12px;
            color: #38bdf8;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .distance-tag strong {
            font-size: 16px;
            color: #fff;
        }

        /* Interactive Controls Panel */
        .controls-box {
            margin-top: 20px;
            background: #0b1424;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 16px;
        }
        .control-row {
            margin-bottom: 16px;
        }
        .control-label-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
            color: #cbd5e1;
        }
        .slider-custom {
            width: 100%;
            -webkit-appearance: none;
            height: 8px;
            border-radius: 4px;
            background: #1e293b;
            outline: none;
            transition: background 0.2s;
        }
        .slider-custom::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            box-shadow: 0 0 10px var(--cyan-glow);
            border: 2px solid #fff;
        }

        /* Quick Action Buttons */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 14px;
        }
        .btn-action {
            background: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-action:hover {
            background: #334155;
            border-color: #64748b;
            color: #fff;
            transform: translateY(-2px);
        }
        .btn-action i { font-size: 1.1rem; color: var(--primary); }
        .btn-action.active {
            background: rgba(6, 182, 212, 0.15);
            border-color: var(--primary);
            color: #38bdf8;
        }

        /* LCD 16x2 Display Component */
        .lcd-display {
            background: #0284c7;
            border: 6px solid #0f172a;
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.6), 0 4px 10px rgba(0,0,0,0.4);
            font-family: 'Fira Code', monospace;
            color: #ffffff;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
        }
        .lcd-line {
            font-size: 15px;
            line-height: 1.6;
            white-space: pre;
            text-shadow: 0 0 8px rgba(255,255,255,0.7);
        }

        /* Status Components Dashboard */
        .hardware-status-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .hw-module {
            background: #070d18;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .hw-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #94a3b8;
        }
        .hw-module.active .hw-icon-box {
            background: rgba(16, 185, 129, 0.15);
            color: var(--accent-green);
        }
        .hw-info h5 {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .hw-value {
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-red);
            display: inline-block;
        }
        .status-dot.active {
            background: var(--accent-green);
            box-shadow: 0 0 8px #34d399;
        }

        /* Tabs for Code and Circuit */
        .code-tabs {
            display: flex;
            gap: 8px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 16px;
        }
        .tab-btn {
            background: none;
            border: none;
            color: #94a3b8;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .tab-btn:hover { color: #fff; }
        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* Code & Terminal Viewers */
        .terminal-panel {
            background: var(--terminal-bg);
            border: 1px solid #1e293b;
            border-radius: 10px;
            font-family: 'Fira Code', monospace;
            font-size: 12.5px;
            color: #e2e8f0;
            overflow: hidden;
        }
        .terminal-header {
            background: #0f172a;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #1e293b;
            font-size: 11.5px;
            color: #94a3b8;
        }
        .terminal-dots {
            display: flex;
            gap: 6px;
        }
        .t-dot { width: 9px; height: 9px; border-radius: 50%; }
        .t-dot.red { background: #ef4444; }
        .t-dot.yellow { background: #f59e0b; }
        .t-dot.green { background: #10b981; }

        .terminal-body {
            padding: 16px;
            max-height: 240px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        /* Syntax Highlight Styles */
        .token-keyword { color: #f43f5e; font-weight: 600; }
        .token-function { color: #38bdf8; }
        .token-number { color: #fbbf24; }
        .token-string { color: #a3e635; }
        .token-comment { color: #64748b; font-style: italic; }

        /* Serial Monitor stream */
        .serial-stream {
            font-family: 'Fira Code', monospace;
            font-size: 12px;
            color: #34d399;
            height: 160px;
            overflow-y: auto;
            padding: 12px 14px;
            background: #030712;
            border-radius: 8px;
            border: 1px solid #1e293b;
            line-height: 1.5;
        }
        .serial-log-line {
            display: block;
            margin-bottom: 4px;
        }
        .serial-log-line .timestamp {
            color: #64748b;
        }

        /* Metric Pill counters */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 16px;
        }
        .metric-pill {
            background: #070d18;
            border: 1px solid #1e293b;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .metric-pill span {
            font-size: 11px;
            color: #94a3b8;
            display: block;
            margin-bottom: 4px;
        }
        .metric-pill strong {
            font-size: 1.1rem;
            color: #fff;
            font-family: 'Fira Code', monospace;
        }

        /* Project Info Banner */
        .spec-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-top: 10px;
        }
        .spec-table th, .spec-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #1e293b;
        }
        .spec-table th {
            color: #94a3b8;
            font-weight: 600;
            width: 35%;
        }
        .spec-table td {
            color: #e2e8f0;
            font-family: 'Fira Code', monospace;
        }
    </style>
</head>
<body>

    <!-- Demo Top Bar -->
    <div class="demo-top-bar">
        <a href="../index.php#projects" target="_top"><i class="fas fa-arrow-left"></i> Return to Portfolio</a>
        <div style="display: flex; align-items: center; gap: 14px;">
            <span class="author-tag"><i class="fas fa-graduation-cap"></i> Sean Adrei Cubio &bull; BSIT Project</span>
            <span style="font-size: 12px; color: #64748b;">Datamex College of St. Adeline</span>
        </div>
    </div>

    <!-- App Header -->
    <header class="app-header">
        <div class="header-content">
            <a href="arduino.php" class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="brand-text">
                    <h1>Arduino Smart Dispenser</h1>
                    <span>Touchless Microcontroller System Live Demo</span>
                </div>
            </a>

            <div class="header-actions">
                <button type="button" class="btn-audio-toggle active" id="audioToggleBtn" onclick="toggleAudio();">
                    <i class="fas fa-volume-up" id="audioIcon"></i>
                    <span id="audioLabel">Sound FX: ON</span>
                </button>
                <a href="../index.php#interview" class="btn-audio-toggle">
                    <i class="fas fa-video"></i> Watch Mock Interview
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="main-container">
        <div class="iot-grid">
            <!-- Left Column: Interactive Hardware Rig -->
            <div class="glass-card">
                <div class="card-header-flex">
                    <h3 class="card-title">
                        <i class="fas fa-cube"></i> Hardware Simulation Rig
                    </h3>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span id="systemModeBadge" style="font-size: 11px; padding: 3px 8px; border-radius: 4px; background: rgba(16, 185, 129, 0.2); color: #34d399; font-weight: 700;">
                            SYSTEM READY
                        </span>
                    </div>
                </div>

                <!-- Dispenser Visual Rig -->
                <div class="dispenser-viewport" id="viewport">
                    <!-- Top Ultrasonic & Spout Mount -->
                    <div class="nozzle-mount">
                        <div class="dispenser-head">
                            <i class="fas fa-bolt" style="color: #fbbf24; margin-right: 5px;"></i> 5V SOLENOID
                        </div>
                        <div class="ultrasonic-sensor" title="HC-SR04 Ultrasonic Sensor">
                            <div class="sensor-eye" title="Transmitter (TRIG)"></div>
                            <span style="font-size: 9px; font-weight: 800; color: #fff;">HC-SR04</span>
                            <div class="sensor-eye" title="Receiver (ECHO)"></div>
                        </div>
                        <div class="spout-pipe"></div>
                    </div>

                    <!-- Animated Sensor Pulses -->
                    <div class="sensor-waves" id="sensorWaves">
                        <div class="wave-ring"></div>
                        <div class="wave-ring"></div>
                        <div class="wave-ring"></div>
                    </div>

                    <!-- Flowing Water Stream -->
                    <div class="water-stream" id="waterStream"></div>

                    <!-- Distance Overlay Tag -->
                    <div class="distance-tag">
                        <span>DETECTION DISTANCE</span>
                        <strong id="displayDistanceVal">6.0 cm</strong>
                        <span id="thresholdStatus" style="color: var(--accent-green); font-size: 10px;">&lt; 10cm (TRIGGERED)</span>
                    </div>

                    <!-- Cup Object -->
                    <div class="cup-container" id="cup" onclick="toggleCupPlacement();" title="Click cup to place or remove">
                        <div class="cup-rim"></div>
                        <div class="glass-cup">
                            <div class="water-fill" id="waterFill">
                                <div class="water-surface"></div>
                            </div>
                            <div class="water-level-label" id="waterLevelPct">0%</div>
                        </div>
                    </div>

                    <!-- Base Platform -->
                    <div class="dispenser-base">
                        <div class="drip-grill"></div>
                    </div>
                </div>

                <!-- Interactive Controls -->
                <div class="controls-box">
                    <div class="control-row">
                        <div class="control-label-bar">
                            <span><i class="fas fa-ruler-horizontal"></i> Ultrasonic Distance Sensor (HC-SR04)</span>
                            <span id="sliderValDisplay" style="color: var(--primary); font-family: 'Fira Code', monospace; font-weight: 700;">6.0 cm</span>
                        </div>
                        <input type="range" min="2" max="35" step="0.5" value="6" class="slider-custom" id="distanceSlider" oninput="handleDistanceChange(this.value)">
                    </div>

                    <div class="quick-actions-grid">
                        <button type="button" class="btn-action active" id="btnPlaceCup" onclick="setCupScenario(6);">
                            <i class="fas fa-glass-whiskey"></i>
                            <span>Place Cup (6cm)</span>
                        </button>
                        <button type="button" class="btn-action" id="btnRemoveCup" onclick="setCupScenario(26);">
                            <i class="fas fa-hand-holding"></i>
                            <span>Remove Cup (26cm)</span>
                        </button>
                        <button type="button" class="btn-action" onclick="resetCupLevel();">
                            <i class="fas fa-redo-alt"></i>
                            <span>Empty Cup (0%)</span>
                        </button>
                    </div>
                </div>

                <!-- Real-time metrics counters -->
                <div class="metrics-row">
                    <div class="metric-pill">
                        <span>Total Water Poured</span>
                        <strong id="totalVolume">180 mL</strong>
                    </div>
                    <div class="metric-pill">
                        <span>Relay Cycle Count</span>
                        <strong id="relayCycles">1</strong>
                    </div>
                    <div class="metric-pill">
                        <span>Sensor Frequency</span>
                        <strong>40 kHz</strong>
                    </div>
                </div>
            </div>

            <!-- Right Column: Microcontroller Dashboard, LCD, & C++ Firmware -->
            <div class="glass-card">
                <div class="card-header-flex">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt"></i> Microcontroller Telemetry
                    </h3>
                    <span style="font-size: 12px; color: #94a3b8; font-family: 'Fira Code', monospace;">
                        ATmega328P @ 16MHz
                    </span>
                </div>

                <!-- Simulated 16x2 LCD Display -->
                <div class="lcd-display">
                    <div class="lcd-line" id="lcdLine1">DISTANCE: 06.0cm  </div>
                    <div class="lcd-line" id="lcdLine2">VALVE:   [ACTIVE] </div>
                </div>

                <!-- Hardware Modules Live Status -->
                <div class="hardware-status-grid">
                    <!-- Relay Module Status -->
                    <div class="hw-module active" id="hwRelay">
                        <div class="hw-icon-box">
                            <i class="fas fa-toggle-on"></i>
                        </div>
                        <div class="hw-info">
                            <h5>5V Relay Switch</h5>
                            <div class="hw-value">
                                <span class="status-dot active" id="relayDot"></span>
                                <span id="relayStatusText">CLOSED (ON)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Solenoid Valve -->
                    <div class="hw-module active" id="hwValve">
                        <div class="hw-icon-box">
                            <i class="fas fa-faucet-drip"></i>
                        </div>
                        <div class="hw-info">
                            <h5>Solenoid Valve</h5>
                            <div class="hw-value">
                                <span class="status-dot active" id="valveDot"></span>
                                <span id="valveStatusText">DISPENSING</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Code Tabs -->
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="switchTab('serial', this);">
                        <i class="fas fa-terminal"></i> Serial Monitor (9600 baud)
                    </button>
                    <button class="tab-btn" onclick="switchTab('code', this);">
                        <i class="fab fa-cuttlefish"></i> Arduino C++ Source Code
                    </button>
                    <button class="tab-btn" onclick="switchTab('pinout', this);">
                        <i class="fas fa-project-diagram"></i> Hardware Wiring
                    </button>
                </div>

                <!-- Tab 1: Serial Monitor Stream -->
                <div id="tab-serial" class="tab-content">
                    <div class="serial-stream" id="serialLog">
                        <span class="serial-log-line"><span class="timestamp">[00:00:01]</span> System Initialized. Ultrasonic Sensor online (Pin 9 Trig, Pin 10 Echo).</span>
                        <span class="serial-log-line"><span class="timestamp">[00:00:02]</span> Threshold set to 10cm. Ready for object detection.</span>
                        <span class="serial-log-line"><span class="timestamp">[00:00:03]</span> Target detected at 6.00 cm &rarr; Energizing Relay pin 7 &rarr; Dispenser OPEN.</span>
                    </div>
                </div>

                <!-- Tab 2: Arduino C++ Source Code -->
                <div id="tab-code" class="tab-content" style="display: none;">
                    <div class="terminal-panel">
                        <div class="terminal-header">
                            <div class="terminal-dots">
                                <div class="t-dot red"></div>
                                <div class="t-dot yellow"></div>
                                <div class="t-dot green"></div>
                            </div>
                            <span>TouchlessWaterDispenser.ino</span>
                        </div>
                        <div class="terminal-body"><span class="token-comment">// Arduino Automatic Touchless Water Dispenser
// Candidate: Sean Adrei Cubio (BSIT Student)</span>
<span class="token-keyword">const int</span> TRIG_PIN = <span class="token-number">9</span>;
<span class="token-keyword">const int</span> ECHO_PIN = <span class="token-number">10</span>;
<span class="token-keyword">const int</span> RELAY_PIN = <span class="token-number">7</span>;
<span class="token-keyword">const int</span> DISTANCE_THRESHOLD = <span class="token-number">10</span>; <span class="token-comment">// cm</span>

<span class="token-keyword">void</span> <span class="token-function">setup</span>() {
  Serial.<span class="token-function">begin</span>(<span class="token-number">9600</span>);
  <span class="token-function">pinMode</span>(TRIG_PIN, OUTPUT);
  <span class="token-function">pinMode</span>(ECHO_PIN, INPUT);
  <span class="token-function">pinMode</span>(RELAY_PIN, OUTPUT);
  <span class="token-function">digitalWrite</span>(RELAY_PIN, HIGH); <span class="token-comment">// Active LOW Relay default OFF</span>
}

<span class="token-keyword">void</span> <span class="token-function">loop</span>() {
  <span class="token-comment">// Send ultrasonic 10us trigger pulse</span>
  <span class="token-function">digitalWrite</span>(TRIG_PIN, LOW);
  <span class="token-function">delayMicroseconds</span>(<span class="token-number">2</span>);
  <span class="token-function">digitalWrite</span>(TRIG_PIN, HIGH);
  <span class="token-function">delayMicroseconds</span>(<span class="token-number">10</span>);
  <span class="token-function">digitalWrite</span>(TRIG_PIN, LOW);

  <span class="token-keyword">long</span> duration = <span class="token-function">pulseIn</span>(ECHO_PIN, HIGH);
  <span class="token-keyword">float</span> distance = duration * <span class="token-number">0.034</span> / <span class="token-number">2</span>;

  <span class="token-keyword">if</span> (distance &gt; <span class="token-number">0</span> &amp;&amp; distance &lt;= DISTANCE_THRESHOLD) {
    <span class="token-function">digitalWrite</span>(RELAY_PIN, LOW); <span class="token-comment">// Turn ON Solenoid</span>
    Serial.<span class="token-function">print</span>(<span class="token-string">"Water ON. Dist: "</span>);
    Serial.<span class="token-function">println</span>(distance);
  } <span class="token-keyword">else</span> {
    <span class="token-function">digitalWrite</span>(RELAY_PIN, HIGH); <span class="token-comment">// Turn OFF Solenoid</span>
  }
  <span class="token-function">delay</span>(<span class="token-number">150</span>);
}</div>
                    </div>
                </div>

                <!-- Tab 3: Hardware Pinout Table -->
                <div id="tab-pinout" class="tab-content" style="display: none;">
                    <div style="background: #050b14; border-radius: 8px; border: 1px solid #1e293b; padding: 12px;">
                        <table class="spec-table">
                            <thead>
                                <tr>
                                    <th>Component</th>
                                    <th>Pin Connection</th>
                                    <th>Function</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>HC-SR04 Trig</td>
                                    <td>Digital Pin 9</td>
                                    <td>Ultrasonic sound pulse trigger (10µs)</td>
                                </tr>
                                <tr>
                                    <td>HC-SR04 Echo</td>
                                    <td>Digital Pin 10</td>
                                    <td>Round-trip bounce receiver timing</td>
                                </tr>
                                <tr>
                                    <td>Relay Module IN</td>
                                    <td>Digital Pin 7</td>
                                    <td>Optocoupler solenoid trigger</td>
                                </tr>
                                <tr>
                                    <td>12V Solenoid</td>
                                    <td>Relay NO &amp; Common</td>
                                    <td>Liquid valve opening diaphragm</td>
                                </tr>
                                <tr>
                                    <td>Power VCC/GND</td>
                                    <td>5V &amp; GND Rail</td>
                                    <td>Shared stable logic power rail</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Student Note Alert Box -->
                <div style="margin-top: 18px; background: rgba(8, 145, 178, 0.1); border: 1px solid rgba(8, 145, 178, 0.3); border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #cbd5e1; display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fas fa-info-circle" style="color: var(--primary); font-size: 1.1rem; margin-top: 2px;"></i>
                    <div>
                        <strong style="color: #fff;">BSIT Student Engineering Context:</strong>
                        This project demonstrates practical embedded hardware control, sensor debounce calculation, and mechanical fluid automation implemented by Sean Adrei Cubio.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Interactive Simulation Scripts -->
    <script>
        // State variables
        let distance = 6.0;
        let isPoured = true;
        let fillLevel = 15;
        let totalWaterMl = 180;
        let relayCycles = 1;
        let soundEnabled = true;
        let audioCtx = null;
        let fillInterval = null;

        // Initialize Web Audio API for sound FX
        function initAudio() {
            if (!audioCtx) {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (AudioContext) {
                    audioCtx = new AudioContext();
                }
            }
        }

        function playRelayClick(turnOn) {
            if (!soundEnabled || !audioCtx) return;
            try {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = turnOn ? 'square' : 'triangle';
                osc.frequency.setValueAtTime(turnOn ? 800 : 400, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(120, audioCtx.currentTime + 0.04);
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.04);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.05);
            } catch (e) {}
        }

        function toggleAudio() {
            soundEnabled = !soundEnabled;
            const btn = document.getElementById('audioToggleBtn');
            const icon = document.getElementById('audioIcon');
            const label = document.getElementById('audioLabel');

            if (soundEnabled) {
                initAudio();
                btn.classList.add('active');
                icon.className = 'fas fa-volume-up';
                label.textContent = 'Sound FX: ON';
            } else {
                btn.classList.remove('active');
                icon.className = 'fas fa-volume-mute';
                label.textContent = 'Sound FX: OFF';
            }
        }

        // Distance & Dispense Logic
        function handleDistanceChange(val) {
            initAudio();
            distance = parseFloat(val);
            document.getElementById('sliderValDisplay').textContent = distance.toFixed(1) + ' cm';
            document.getElementById('displayDistanceVal').textContent = distance.toFixed(1) + ' cm';

            updateSimulation();
        }

        function updateSimulation() {
            const threshold = 10.0;
            const isDetected = (distance <= threshold);

            const stream = document.getElementById('waterStream');
            const threshStatus = document.getElementById('thresholdStatus');
            const lcdLine1 = document.getElementById('lcdLine1');
            const lcdLine2 = document.getElementById('lcdLine2');
            const hwRelay = document.getElementById('hwRelay');
            const hwValve = document.getElementById('hwValve');
            const relayDot = document.getElementById('relayDot');
            const valveDot = document.getElementById('valveDot');
            const relayText = document.getElementById('relayStatusText');
            const valveText = document.getElementById('valveStatusText');
            const waves = document.getElementById('sensorWaves');

            const distStr = (distance < 10 ? '0' : '') + distance.toFixed(1) + 'cm';

            if (isDetected) {
                // ACTIVE STATE
                if (!isPoured) {
                    playRelayClick(true);
                    relayCycles++;
                    document.getElementById('relayCycles').textContent = relayCycles;
                    logSerial(`Object detected at ${distStr} -> Solenoid Valve TRIGGERED.`);
                }
                isPoured = true;
                stream.classList.add('pouring');
                waves.style.opacity = '1';

                threshStatus.textContent = '< 10cm (TRIGGERED)';
                threshStatus.style.color = 'var(--accent-green)';

                lcdLine1.textContent = `DISTANCE: ${distStr}  `;
                lcdLine2.textContent = `VALVE:   [ACTIVE] `;

                hwRelay.classList.add('active');
                hwValve.classList.add('active');
                relayDot.classList.add('active');
                valveDot.classList.add('active');
                relayText.textContent = 'CLOSED (ON)';
                valveText.textContent = 'DISPENSING';

                startFilling();
            } else {
                // STANDBY STATE
                if (isPoured) {
                    playRelayClick(false);
                    logSerial(`Object cleared (${distStr}) -> Solenoid Valve CLOSED.`);
                }
                isPoured = false;
                stream.classList.remove('pouring');
                waves.style.opacity = '0.3';

                threshStatus.textContent = '> 10cm (STANDBY)';
                threshStatus.style.color = '#94a3b8';

                lcdLine1.textContent = `DISTANCE: ${distStr}  `;
                lcdLine2.textContent = `VALVE:   [STANDBY]`;

                hwRelay.classList.remove('active');
                hwValve.classList.remove('active');
                relayDot.classList.remove('active');
                valveDot.classList.remove('active');
                relayText.textContent = 'OPEN (OFF)';
                valveText.textContent = 'CLOSED';

                stopFilling();
            }
        }

        function startFilling() {
            if (fillInterval) return;
            fillInterval = setInterval(() => {
                if (isPoured && fillLevel < 100) {
                    fillLevel += 2;
                    totalWaterMl += 4;
                    document.getElementById('waterFill').style.height = fillLevel + '%';
                    document.getElementById('waterLevelPct').textContent = fillLevel + '%';
                    document.getElementById('totalVolume').textContent = totalWaterMl + ' mL';

                    if (fillLevel >= 100) {
                        document.getElementById('waterLevelPct').textContent = 'FULL!';
                    }
                }
            }, 100);
        }

        function stopFilling() {
            if (fillInterval) {
                clearInterval(fillInterval);
                fillInterval = null;
            }
        }

        function setCupScenario(dist) {
            initAudio();
            const slider = document.getElementById('distanceSlider');
            slider.value = dist;
            document.getElementById('cup').classList.toggle('removed', dist > 15);

            document.getElementById('btnPlaceCup').classList.toggle('active', dist <= 10);
            document.getElementById('btnRemoveCup').classList.toggle('active', dist > 10);

            handleDistanceChange(dist);
        }

        function toggleCupPlacement() {
            const isRemoved = document.getElementById('cup').classList.contains('removed');
            if (isRemoved) {
                setCupScenario(6);
            } else {
                setCupScenario(26);
            }
        }

        function resetCupLevel() {
            fillLevel = 0;
            document.getElementById('waterFill').style.height = '0%';
            document.getElementById('waterLevelPct').textContent = '0%';
            logSerial('Cup emptied by user. Ready for refilling.');
        }

        function logSerial(msg) {
            const serialLog = document.getElementById('serialLog');
            const now = new Date();
            const timeStr = now.toTimeString().split(' ')[0];
            const line = document.createElement('span');
            line.className = 'serial-log-line';
            line.innerHTML = `<span class="timestamp">[${timeStr}]</span> ${msg}`;
            serialLog.appendChild(line);
            serialLog.scrollTop = serialLog.scrollHeight;
        }

        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

            btn.classList.add('active');
            const target = document.getElementById('tab-' + tabId);
            if (target) target.style.display = 'block';
        }

        // Initialize simulation on page load
        window.addEventListener('DOMContentLoaded', () => {
            updateSimulation();
        });
    </script>
</body>
</html>
