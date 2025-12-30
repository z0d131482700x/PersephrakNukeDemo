# PersephrakNukeDemo
# PersephrakNuke.php - Ransomware Demo for Red Team Training

## Overview

PersephrakNuke.php is a harmless mockup demonstration of ransomware behavior patterns designed for red team security awareness training. This educational tool simulates the destructive capabilities of real ransomware without causing any actual damage, encryption, or data loss.

The demo creates `.persephrak` extension files and deploys a maintenance page to illustrate common ransomware tactics observed in the wild.

## Key Features

- Creates mock `.persephrak` files (no real encryption)
- Adds warning banners to original files (no deletion)
- Deploys educational maintenance page
- Includes full reset functionality
- Password-protected activation (Argon2id)
- Processes only small files (<1MB) for safety
- 100% reversible operation

## Safety Guarantees

- No AES encryption performed
- No original files deleted
- All content preserved and accessible
- Clear visual indicators of demo mode
- Simple one-click reset capability

## Usage

1. Upload `PersephrakNukeDemo.php` to target web directory
2. Access via web browser
3. Enter demo password: `WontHacked@667755` (or generate your own)
4. Click "Run Demo Attack" to simulate ransomware deployment
5. Observe `.persephrak` files and maintenance page
6. Use "Reset Demo" with same password to restore

## Demo Workflow
Normal site operation
↓

Demo activation (.persephrak_demo_active created)
↓

Mock files generated (originals + warning banners)
↓

index.php replaced with educational maintenance page
↓

Reset removes all demo artifacts


## Technical Details

**Password Hash**: Argon2id (v19, m=80,t=20,p=10)  
**File Processing**: Recursive directory scan (excludes `index.php`, demo files)  
**Mock Content**: Preserves original + safety warnings  
**Reset Mechanism**: Single file deletion + index.php restoration  

## Red Team Applications

- Security awareness training
- Ransomware behavior demonstration
- Incident response familiarization
- Penetration testing education
- Defensive security workshops

## Deployment Requirements

- PHP 7.2+ with Argon2 support
- Web server with directory listing disabled
- Write permissions on target directory
- Test environment recommended

## Reset Instructions

1. Access demo control panel
2. Enter admin password
3. Click "Reset Demo"
4. Delete `.persephrak_demo_active` manually if needed
5. Remove `.persephrak` files and restore `index.php`

## Limitations

- Single-directory operation only
- Small file processing (<1MB)
- No database simulation
- Web server environment required

## Legal and Ethical Use

This tool is provided strictly for:
- Authorized red team engagements
- Security training environments
- Educational demonstrations
- Defensive security research

Unauthorized deployment on production systems constitutes unlawful access.

## Author
coded by @z0d131482700x 
Persephrak Red/blue Team Security Research  
For educational and authorized testing purposes only

proof: https://shorturl.at/cu5wm
