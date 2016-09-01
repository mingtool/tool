用户名 密码
cjadmin
cjadmin8865


数据库:
CREATE TABLE `map_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `sort` VARCHAR(45) NOT NULL,
  `creattime` INT NOT NULL,
  PRIMARY KEY (`id`));
  ALTER TABLE `mapadmin`.`map_group` 
  ADD COLUMN `is_delete` VARCHAR(45) NOT NULL DEFAULT 0 AFTER `sort`;

  

  CREATE TABLE `map_point` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `group_id` VARCHAR(45) NOT NULL,
  `remark` VARCHAR(500) NOT NULL,
  `pics` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `point` VARCHAR(30) NOT NULL,

  `address` VARCHAR(100) NOT NULL,
  `url_address` VARCHAR(150) NOT NULL,
  `createtime` INT NOT NULL,
  PRIMARY KEY (`id`));


  环境:
  PHP5.0以上  yaf扩展

  apache 需要配置 重定向
  以nginx为例:
  location / {
              if (!-e $request_filename) {
                  rewrite ^.*$ /index.php;
              }
          }


  根目录需指向 public

  域名确定后 需要修改  conf/application.ini
    application.baseUrl   = 'http://mapadmin.ming.cn'
    view.baseUrl.cdn = 'http://mapadmin.ming.cn/static/'



  public/pics 目录需要设置成可写 

  