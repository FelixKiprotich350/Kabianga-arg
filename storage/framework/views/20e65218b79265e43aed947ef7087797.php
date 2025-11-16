<!DOCTYPE html>
<html>
<head>
    <title>Kabianga ARG Portal API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script>
        SwaggerUIBundle({
            url: '<?php echo e(url("/api/docs.json")); ?>',
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIBundle.presets.standalone
            ]
        });
    </script>
</body>
</html><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/swagger-ui.blade.php ENDPATH**/ ?>