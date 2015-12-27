<!DOCTYPE html>
<html>
    <head>
        <title>Phumin Obfuscator</title>
        <script src="jqueyr.js"></script>
        <script>
            $(document).ready(function () {
                $('#files').on('change', function (e) {
                    var files = e.target.files; // FileList
                    uploadFiles(files);
                });
            });
            function uploadFiles(files) {
                // Create a new HTTP requests, Form data item (data we will send to the server) and an empty string for the file paths.
                xhr = new XMLHttpRequest();
                data = new FormData();
                paths = "";

                // Set how to handle the response text from the server
                xhr.onreadystatechange = function (ev) {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        $("#output").html(xhr.responseText);
                    }
                };

                // Loop through the file list
                for (var i in files) {
                    // Append the current file path to the paths variable (delimited by tripple hash signs - ###)
                    paths += files[i].webkitRelativePath + "###";
                    // Append current file to our FormData with the index of i
                    data.append(i, files[i]);
                }

                // Append the paths variable to our FormData to be sent to the server
                // Currently, As far as I know, HTTP requests do not natively carry the path data
                // So we must add it to the request manually.
                data.append('paths', paths);

                // Open and send HHTP requests to upload.php
                xhr.open('POST', "compiler.php", true);
                xhr.send(this.data);
            }
        </script>
    </head>
    <body>
    <center>
        <center>
            <h1>Upload your files & Encode your file</h1>
            <span>Upload once folder at time only</span><br><br>
            <input type="file" id="files" name="files[]" multiple webkitdirectory />
            <pre id="output"></pre>
        </center>
    </center>
</body>
</html>
