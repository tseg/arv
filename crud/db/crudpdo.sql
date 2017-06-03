CREATE TABLE IF NOT EXISTS `crudpdo` (
`id_pdo` int(11) NOT NULL,
  `nm_pdo` varchar(45) NOT NULL,
  `gd_pdo` varchar(20) NOT NULL,
  `tl_pdo` varchar(25) NOT NULL,
  `ar_pdo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `crudpdo`
 ADD PRIMARY KEY (`id_pdo`);

ALTER TABLE `crudpdo`
MODIFY `id_pdo` int(11) NOT NULL AUTO_INCREMENT;

