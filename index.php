<?php


$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");
$slug = '';
if(isset($_POST["create"]))
{
 //$slug = preg_replace(pattern, replacement, subject)
 $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($_POST["title"])));

 $query = "SELECT slug_url FROM slug WHERE slug_url LIKE '$slug%'";
 
 $statement = $connect->prepare($query); 
 if($statement->execute())
 {
  $total_row = $statement->rowCount();
  if($total_row > 0)
  {
   $result = $statement->fetchAll();
   foreach($result as $row)
   {
    $data[] = $row['slug_url'];
   }
   
   if(in_array($slug, $data))
   {
    $count = 0;
    while( in_array( ($slug . '-' . ++$count ), $data) );
    $slug = $slug . '-' . $count;
   }
  }
 }

 $insert_data = array(
  ':slug_title'  => $_POST['title'],
  ':slug_url'   => $slug
 );
 $query = "INSERT INTO slug (slug_title, slug_url) VALUES (:slug_title, :slug_url)";
 $statement = $connect->prepare($query);
 $statement->execute($insert_data);

 //echo $slug;
}

?>
<!DOCTYPE html>
<html>
 <head>
  <title>How Create Unique Slug in PHP</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <style>
  .box
  {
   max-width:600px;
   width:100%;
   margin: 0 auto;;
  }
  </style>
 </head>
 <body>
  <div class="container box">
   <br />
   <h3 align="center">How Create Unique Slug in PHP</h3>
   <br />
   <form method="post">
    <div class="form-group">
     <label>Enter Title for Slug</label>
     <input type="text" name="title" class="form-control" required />
    </div>
    <br />
    <div class="form-group">
     <input type="submit" name="create" class="btn btn-info" value="Create" />
    </div>
    <br />
    <h4>Generated Slug - <?php echo $slug; ?></h4>
   </form>
  </div>
 </body>
</html>
