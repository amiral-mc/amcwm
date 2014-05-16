بيانات العضوية في الدليل
<br />
اسم الشركة: <?php echo $company?>
<br />
اسم المستخدم: <?php echo $username?>
<?php if($password):?>
<br />
كلمة المرور : <?php echo $password?>
<?php endif;?>
<br />
لتجديث بيانات الشركة اضغط علي الرابط التالي:
<br />
<a href="<?php echo $link?>"><?php echo $link?></a>