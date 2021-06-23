<?php include('header.php'); ?>
	
	    <!-- Page Content -->
    <div class="container">

      <div class="row">
	
		<?php
$link='';
$where='';
if(isset($_GET["cat"])) {
    $cat = intval($_GET["cat"]);
    if($cat != 0) {
         $where = "WHERE category_id = $cat";
         $link = "cat=$cat&";
    }
}

$sql = "SELECT count(*) FROM posts $where";
$total_records = $homelib->get_row_number($sql);

$limit = 1; // chỉ định hiển thị 3 bài viết trên một trang

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$total_page = ceil($total_records / $limit);

if ($current_page > $total_page){
    $current_page = $total_page;
}
else if ($current_page < 1) {
    $current_page = 1;
}

$start = ($current_page - 1) * $limit;

$sql = "SELECT * FROM posts $where ORDER BY createdate DESC LIMIT $start, $limit";
$data = $homelib->get_list($sql);
?>
      
      <!-- Blog Entries Column -->
      <div class="col-md-8">

        <h1 class="my-4">Siêu Hot
          <small>Trang chi tiết</small>
        </h1>
        
        <?php 
			for ($i = 0; $i < count($data); $i++) {
			?>
	          <div class="card mb-4">
	            <img class="card-img-top" src="images/<?php echo $data[$i]['image'];?>" height="500px" alt="Card image cap">
	            <div class="card-body">
	              <h2 class="card-title"><?php echo $data[$i]['title'];?></h2>
	              <p class="card-text"><?php echo substr($data[$i]['content'], 0, 5000).'...';?></p>
	              <!-- <a href="newsdetail.php" class="btn btn-primary">Xem thêm &rarr;</a> -->
	            </div>
	          </div>
		  <?php	
			}
		  ?>

      
        <!-- Pagination -->
        <ul class="pagination justify-content-center mb-4">
				<?php 
				// phần hiển thị phân trang
				// nếu current_page > 1 và total_page > 1 mới hiển thị nút prev
				if ($current_page > 1 && $total_page > 1) {
				    echo '<li class="page-item"><a class="page-link" href="newsdetail.php?'.$link.'page='.($current_page-1).'">Older</a></li>';
				}
				
				for ($i = 1; $i <= $total_page; $i++)
				{
				    if ($current_page == $i) {
				        echo '<li class="page-item disabled"><a class="page-link" href="#">'.$i.'</a></li>';
				    }else {
				    echo '<li class="page-item"><a class="page-link" href="newsdetail.php?'.$link.'page='.$i.'">'.$i.'</a></li>';
				    }
				}
				
				// nếu current_page < $total_page và total_page > 1 mới hiển thị nút next
				if ($current_page <$total_page && $total_page > 1) {
				    echo '<li class="page-item"><a class="page-link" href="newsdetail.php?'.$link.'page='.($current_page+1).'">Newer</a></li>';
				}
				?>
				</ul>

      </div>
		
		
		<?php include('sidebar.php'); ?>
		
	 </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
	
<?php include('footer.php'); ?>
