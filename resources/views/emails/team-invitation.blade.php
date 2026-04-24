<!DOCTYPE html>
<html>
<head>
    <title>Team Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 40px; text-align: center;">
    <div style="max-w: 500px; margin: 0 auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #111827; margin-bottom: 20px;">You've been invited!</h2>
        <p style="color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
            <strong>{{ $inviterName }}</strong> has invited you to join their team <strong>{{ $tenantName }}</strong> on PlayStation Pro.
        </p>
        <a href="{{ route('invitations.accept', $token) }}" 
           style="display: inline-block; background-color: #111827; color: white; padding: 14px 30px; text-decoration: none; border-radius: 12px; font-weight: bold;">
            Accept Invitation
        </a>
        <p style="color: #9ca3af; font-size: 13px; margin-top: 30px;">
            If you do not recognize this invitation, you can safely ignore this email.
        </p>
    </div>
</body>
</html>
