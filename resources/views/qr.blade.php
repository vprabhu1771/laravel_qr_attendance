<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h2>Generated QR Code</h2>
        
        {{-- Display the generated QR code --}}
        {!! $qrCode !!}
        
        <p style="margin-top: 20px;">Identifier: {{ $event }}</p>
    </div>
</body>
</html>