<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'University of Kabianga - ARG Portal'); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; }
        .email-container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: #fff; padding: 30px 20px; text-align: center; }
        .header h1 { font-size: 24px; font-weight: 600; margin-bottom: 5px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: 500; color: #1e40af; margin-bottom: 20px; }
        .message { font-size: 16px; line-height: 1.7; margin-bottom: 25px; }
        .action-button { display: inline-block; padding: 14px 28px; background: #1e40af; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 500; margin: 20px 0; transition: background 0.3s; }
        .action-button:hover { background: #1d4ed8; }
        .info-box { background: #f8fafc; border-left: 4px solid #3b82f6; padding: 15px 20px; margin: 20px 0; border-radius: 4px; }
        .footer { background: #f8fafc; padding: 25px 30px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 14px; color: #6b7280; margin-bottom: 10px; }
        .contact-info { font-size: 13px; color: #9ca3af; }
        .divider { height: 1px; background: #e5e7eb; margin: 25px 0; }
        @media (max-width: 600px) {
            .email-container { margin: 10px; border-radius: 0; }
            .content { padding: 25px 20px; }
            .header { padding: 25px 20px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>University of Kabianga</h1>
            <p>Annual Research Grants Portal</p>
        </div>
        
        <div class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        
        <div class="footer">
            <p><strong>University of Kabianga</strong></p>
            <p>Research & Innovation Office</p>
            <div class="contact-info">
                <p>P.O. Box 2030-20200, Kericho, Kenya</p>
                <p>Email: research@kabianga.ac.ke | Phone: +254 52 30301</p>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/emails/layouts/master.blade.php ENDPATH**/ ?>