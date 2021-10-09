
SET NAMES utf8;


-- Structure of table `0_areas` --

DROP TABLE IF EXISTS `0_areas`;

CREATE TABLE `0_areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_areas` --

INSERT INTO `0_areas` VALUES
('1', 'Địa phương', '0');

-- Structure of table `0_attachments` --

DROP TABLE IF EXISTS `0_attachments`;

CREATE TABLE `0_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `unique_name` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `filename` varchar(60) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `filetype` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_no` (`type_no`,`trans_no`)
) ENGINE=InnoDB;

-- Data of table `0_attachments` --

-- Structure of table `0_audit_trail` --

DROP TABLE IF EXISTS `0_audit_trail`;

CREATE TABLE `0_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `user` smallint(6) unsigned NOT NULL DEFAULT '0',
  `stamp` timestamp NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL DEFAULT '0',
  `gl_date` date NOT NULL DEFAULT '0000-00-00',
  `gl_seq` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Seq` (`fiscal_year`,`gl_date`,`gl_seq`),
  KEY `Type_and_Number` (`type`,`trans_no`)
) ENGINE=InnoDB;

-- Data of table `0_audit_trail` --

-- Structure of table `0_bank_accounts` --

DROP TABLE IF EXISTS `0_bank_accounts`;

CREATE TABLE `0_bank_accounts` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_type` smallint(6) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(60) NOT NULL DEFAULT '',
  `bank_account_number` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(60) NOT NULL DEFAULT '',
  `bank_address` tinytext,
  `bank_curr_code` char(3) NOT NULL DEFAULT '',
  `dflt_curr_act` tinyint(1) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `bank_charge_act` varchar(15) NOT NULL DEFAULT '',
  `last_reconciled_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending_reconcile_balance` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bank_account_name` (`bank_account_name`),
  KEY `bank_account_number` (`bank_account_number`),
  KEY `account_code` (`account_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 ;

-- Data of table `0_bank_accounts` --

INSERT INTO `0_bank_accounts` VALUES
('1121', 0, 'ACB', 'N/A', 'Ngân hàng Á Châu', 'Hà Nội', 'VND', 1, 1, '6428', '0000-00-00 00:00:00', 0, 0),
('1124', 3, 'VCB', '', 'Vietcombank', 'Hà Nội', 'VND', 0, 2, '6428', '0000-00-00 00:00:00', 0, 0);

-- Structure of table `0_bank_trans` --

DROP TABLE IF EXISTS `0_bank_trans`;

CREATE TABLE `0_bank_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `bank_act` varchar(15) NOT NULL DEFAULT '',
  `ref` varchar(40) DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) NOT NULL DEFAULT '0',
  `person_id` tinyblob,
  `reconciled` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_act` (`bank_act`,`ref`),
  KEY `type` (`type`,`trans_no`),
  KEY `bank_act_2` (`bank_act`,`reconciled`),
  KEY `bank_act_3` (`bank_act`,`trans_date`)
) ENGINE=InnoDB;

-- Data of table `0_bank_trans` --

-- Structure of table `0_bom` --

DROP TABLE IF EXISTS `0_bom`;

CREATE TABLE `0_bom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` char(20) NOT NULL DEFAULT '',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentre_added` int(11) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`parent`,`component`,`workcentre_added`,`loc_code`),
  KEY `component` (`component`),
  KEY `id` (`id`),
  KEY `loc_code` (`loc_code`),
  KEY `parent` (`parent`,`loc_code`),
  KEY `workcentre_added` (`workcentre_added`)
) ENGINE=InnoDB;

-- Data of table `0_bom` --

-- Structure of table `0_budget_trans` --

DROP TABLE IF EXISTS `0_budget_trans`;

CREATE TABLE `0_budget_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Account` (`account`,`tran_date`,`dimension_id`,`dimension2_id`)
) ENGINE=InnoDB;

-- Data of table `0_budget_trans` --

-- Structure of table `0_chart_class` --

DROP TABLE IF EXISTS `0_chart_class`;

CREATE TABLE `0_chart_class` (
  `cid` varchar(3) NOT NULL,
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB;

-- Data of table `0_chart_class` --

INSERT INTO `0_chart_class` VALUES
('1', 'Tài sản ngắn hạn', 1, 0),
('2', 'Tài sản dài hạn', 1, 0),
('3', 'Nợ phải trả', 2, 0),
('4', 'Vốn chủ sở hữu', 2, 0),
('5', 'Doanh thu', 4, 0),
('6', 'Chi phí sản xuất, kinh doanh', 6, 0),
('7', 'Thu nhập khác', 4, 0),
('8', 'Chi phí khác', 6, 0),
('9', 'Xác định kết quả kinh doanh', 6, 0);

-- Structure of table `0_chart_master` --

DROP TABLE IF EXISTS `0_chart_master`;

CREATE TABLE `0_chart_master` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_code2` varchar(15) NOT NULL DEFAULT '',
  `account_name` varchar(60) NOT NULL DEFAULT '',
  `account_type` varchar(10) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_code`),
  KEY `account_name` (`account_name`),
  KEY `accounts_by_type` (`account_type`,`account_code`)
) ENGINE=InnoDB;

-- Data of table `0_chart_master` --

INSERT INTO `0_chart_master` VALUES
('1111', '', 'Tiền Việt Nam', '111', '0'),
('1112', '', 'Ngoại tệ', '111', '0'),
('1113', '', 'Vàng tiền tệ', '111', '0'),

('1121', '', 'Tiền Việt Nam', '112', '0'),
('1122', '', 'Ngoại tệ', '112', '0'),
('1123', '', 'Vàng tiền tệ', '112', '0'),
('1124', '', 'Tiền gửi ngân hàng dùng cho thanh toán', '112', '0'),

('1131', '', 'Tiền Việt Nam', '113', '0'),
('1132', '', 'Ngoại tệ', '113', '0'),

('1211', '', 'Cổ phiếu', '121', '0'),
('1212', '', 'Trái phiếu', '121', '0'),
('1218', '', 'Chứng khoán và công cụ tài chính khác', '121', '0'),

('1281', '', 'Tiền gửi có kỳ hạn', '128', '0'),
('1282', '', 'Trái phiếu', '128', '0'),
('1283', '', 'Cho vay', '128', '0'),
('1288', '', 'Các khoản đầu tư khác nắm giữ đến ngày đáo hạn', '128', '0'),

('131', '', 'Phải thu của khách hàng', '131', '0'),

('1331', '', 'Thuế GTGT được khấu trừ của hàng hóa, dịch vụ', '133', '0'),
('1332', '', 'Thuế GTGT được khấu trừ của TSCĐ', '133', '0'),

('1361', '', 'Vốn kinh doanh ở các đơn vị trực thuộc', '136', '0'),
('1362', '', 'Phải thu nội bộ về chênh lệch tỷ giá', '136', '0'),
('1363', '', 'Phải thu nội bộ về chi phí đi vay đủ điều kiện được vốn hoá', '136', '0'),
('1368', '', 'Phải thu nội bộ khác', '136', '0'),

('1381', '', 'Tài sản thiếu chờ xử lý', '138', '0'),
('1385', '', 'Phải thu về cổ phần hoá', '138', '0'),
('1388', '', 'Phải thu khác', '138', '0'),

('141', '', 'Tạm ứng', '141', '0'),

('151', '', 'Hàng mua đang đi đường', '151', '0'),

('152', '', 'Nguyên liệu, vật liệu', '152', '0'),

('1531', '', 'Công cụ, dụng cụ', '153', '0'),
('1532', '', 'Bao bì luân chuyển', '153', '0'),
('1533', '', 'Đồ dùng cho thuê', '153', '0'),
('1534', '', 'Thiết bị, phụ tùng thay thế', '153', '0'),

('154', '', 'Chi phí sản xuất, kinh doanh dở dang', '154', '0'),

('1551', '', 'Thành phẩm nhập kho', '155', '0'),
('1557', '', 'Thành phẩm bất động sản', '155', '0'),

('1561', '', 'Giá mua hàng hóa', '156', '0'),
('1562', '', 'Chi phí thu mua hàng hóa', '156', '0'),
('1567', '', 'Hàng hóa bất động sản', '156', '0'),

('157', '', 'Hàng gửi đi bán', '157', '0'),

('158', '', 'Hàng hoá kho bảo thuế', '158', '0'),

('1611', '', 'Chi sự nghiệp năm trước', '161', '0'),
('1612', '', 'Chi sự nghiệp năm nay', '161', '0'),

('171', '', 'Giao dịch mua bán lại trái phiếu chính phủ', '171', '0'),

('2111', '', 'Nhà cửa, vật kiến trúc', '211', '0'),
('2112', '', 'Máy móc, thiết bị', '211', '0'),
('2113', '', 'Phương tiện vận tải, truyền dẫn', '211', '0'),
('2114', '', 'Thiết bị, dụng cụ quản lý', '211', '0'),
('2115', '', 'Cây lâu năm, súc vật làm việc và cho sản phẩm', '211', '0'),
('2118', '', 'TSCĐ khác', '211', '0'),

('2121', '', 'TSCĐ hữu hình thuê tài chính.', '212', '0'),
('2122', '', 'TSCĐ vô hình thuê tài chính.', '212', '0'),

('2131', '', 'Quyền sử dụng đất', '213', '0'),
('2132', '', 'Quyền phát hành', '213', '0'),
('2133', '', 'Bản quyền, bằng sáng chế', '213', '0'),
('2134', '', 'Nhãn hiệu, tên thương mại', '213', '0'),
('2135', '', 'Chương trình phần mềm', '213', '0'),
('2136', '', 'Giấy phép và giấy phép nhượng quyền', '213', '0'),
('2138', '', 'TSCĐ vô hình khác', '213', '0'),

('2141', '', 'Hao mòn TSCĐ hữu hình', '214', '0'),
('2142', '', 'Hao mòn TSCĐ thuê tài chính', '214', '0'),
('2143', '', 'Hao mòn TSCĐ vô hình', '214', '0'),
('2147', '', 'Hao mòn bất động sản đầu tư', '214', '0'),

('217', '', 'Bất động sản đầu tư', '217', '0'),

('221', '', 'Đầu tư vào công ty con', '221', '0'),

('222', '', 'Đầu tư vào công ty liên doanh, liên kết', '222', '0'),

('2281', '', 'Đầu tư góp vốn vào đơn vị khác', '228', '0'),
('2288', '', 'Đầu tư khác', '228', '0'),

('2291', '', 'Dự phòng giảm giá chứng khoán kinh doanh', '229', '0'),
('2292', '', 'Dự phòng tổn thất đầu tư vào đơn vị khác', '229', '0'),
('2293', '', 'Dự phòng phải thu khó đòi', '229', '0'),
('2294', '', 'Dự phòng giảm giá hàng tồn kho', '229', '0'),

('2411', '', 'Mua sắm TSCĐ', '241', '0'),
('2412', '', 'Xây dựng cơ bản', '241', '0'),
('2413', '', 'Sửa chữa lớn TSCĐ', '241', '0'),

('242', '', 'Chi phí trả trước', '242', '0'),

('243', '', 'Tài sản thuế thu nhập hoãn lại', '243', '0'),

('244', '', 'Cầm cố, thế chấp, ký quỹ, ký cược', '244', '0'),

('331', '', 'Phải trả cho người bán', '331', '0'),

('33311', '', 'Thuế GTGT đầu ra', '3331', '0'),
('33312', '', 'Thuế GTGT hàng nhập khẩu', '3331', '0'),
('3332', '', 'Thuế tiêu thụ đặc biệt', '333', '0'),
('3333', '', 'Thuế xuất, nhập khẩu', '333', '0'),
('3334', '', 'Thuế thu nhập doanh nghiệp', '333', '0'),
('3335', '', 'Thuế thu nhập cá nhân', '333', '0'),
('3336', '', 'Thuế tài nguyên', '333', '0'),
('3337', '', 'Thuế nhà đất, tiền thuê đất', '333', '0'),
('33381', '', 'Thuế bảo vệ môi trường', '3338', '0'),
('33382', '', 'Các loại thuế khác', '3338', '0'),
('3339', '', 'Phí, lệ phí và các khoản phải nộp khác', '333', '0'),

('3341', '', 'Phải trả công nhân viên', '334', '0'),
('3348', '', 'Phải trả người lao động khác', '334', '0'),

('335', '', 'Chi phí phải trả', '335', '0'),

('3361', '', 'Phải trả nội bộ về vốn kinh doanh', '336', '0'),
('3362', '', 'Phải trả nội bộ về chênh lệch tỷ giá', '336', '0'),
('3363', '', 'Phải trả nội bộ về chi phí đi vay đủ điều kiện được vốn hoá', '336', '0'),
('3368', '', 'Phải trả nội bộ khác', '336', '0'),

('337', '', 'Thanh toán theo tiến độ kế hoạch hợp đồng xây dựng', '337', '0'),

('3381', '', 'Tài sản thừa chờ giải quyết', '338', '0'),
('3382', '', 'Kinh phí công đoàn', '338', '0'),
('3383', '', 'Bảo hiểm xã hội', '338', '0'),
('3384', '', 'Bảo hiểm y tế', '338', '0'),
('3385', '', 'Phải trả về cổ phần hoá', '338', '0'),
('3386', '', 'Bảo hiểm thất nghiệp', '338', '0'),
('3387', '', 'Doanh thu chưa thực hiện', '338', '0'),
('3388', '', 'Phải trả, phải nộp khác', '338', '0'),

('3411', '', 'Các khoản đi vay', '341', '0'),
('3412', '', 'Nợ thuê tài chính', '341', '0'),

('34311', '', 'Mệnh giá', '3431', '0'),
('34312', '', 'Chiết khấu trái phiếu', '3431', '0'),
('34313', '', 'Phụ trội trái phiếu', '3431', '0'),
('3432', '', 'Trái phiếu chuyển đổi', '343', '0'),

('344', '', 'Nhận ký quỹ, ký cược', '344', '0'),

('347', '', 'Thuế thu nhập hoãn lại phải trả', '347', '0'),

('3521', '', 'Dự phòng bảo hành sản phẩm hàng hóa', '352', '0'),
('3522', '', 'Dự phòng bảo hành công trình xây dựng', '352', '0'),
('3523', '', 'Dự phòng tái cơ cấu doanh nghiệp', '352', '0'),
('3524', '', 'Dự phòng phải trả khác', '352', '0'),

('3531', '', 'Quỹ khen thưởng', '353', '0'),
('3532', '', 'Quỹ phúc lợi', '353', '0'),
('3533', '', 'Quỹ phúc lợi đã hình thành TSCĐ', '353', '0'),
('3534', '', 'Quỹ thưởng ban quản lý điều hành công ty', '353', '0'),

('3561', '', 'Quỹ phát triển khoa học và công nghệ', '356', '0'),
('3562', '', 'Quỹ phát triển khoa học và công nghệ đã hình thành TSCĐ', '356', '0'),

('357', '', 'Quỹ bình ổn giá', '357', '0'),

('41111', '', 'Cổ phiếu phổ thông có quyền biểu quyết', '4111', '0'),
('41112', '', 'Cổ phiếu ưu đãi', '4111', '0'),
('4112', '', 'Thặng dư vốn cổ phần', '411', '0'),
('4113', '', 'Quyền chọn chuyển đổi trái phiếu', '411', '0'),
('4118', '', 'Vốn khác', '411', '0'),

('412', '', 'Chênh lệch đánh giá lại tài sản', '412', '0'),

('4131', '', 'Chênh lệch tỷ giá do đánh giá lại các khoản mục tiền tệ có gốc ngoại tệ', '413', '0'),
('4132', '', 'Chênh lệch tỷ giá hối đoái trong giai đoạn trước hoạt động', '413', '0'),

('414', '', 'Quỹ đầu tư phát triển', '414', '0'),

('417', '', 'Quỹ hỗ trợ sắp xếp doanh nghiệp', '417', '0'),

('418', '', 'Các quỹ khác thuộc vốn chủ sở hữu', '418', '0'),

('419', '', 'Cổ phiếu quỹ', '419', '0'),

('4211', '', 'Lợi nhuận sau thuế chưa phân phối năm trước', '421', '0'),
('4212', '', 'Lợi nhuận sau thuế chưa phân phối năm nay', '421', '0'),

('441', '', 'Nguồn vốn đầu tư xây dựng cơ bản', '441', '0'),

('4611', '', 'Nguồn kinh phí sự nghiệp năm trước', '461', '0'),
('4612', '', 'Nguồn kinh phí sự nghiệp năm nay', '461', '0'),

('466', '', 'Nguồn kinh phí đã hình thành TSCĐ', '466', '0'),

('5111', '', 'Doanh thu bán hàng hóa', '511', '0'),
('5112', '', 'Doanh thu bán các thành phẩm', '511', '0'),
('5113', '', 'Doanh thu cung cấp dịch vụ', '511', '0'),
('5114', '', 'Doanh thu trợ cấp, trợ giá', '511', '0'),
('5117', '', 'Doanh thu kinh doanh bất động sản đầu tư', '511', '0'),
('5118', '', 'Doanh thu khác', '511', '0'),

('515', '', 'Doanh thu hoạt động tài chính', '515', '0'),

('5211', '', 'Chiết khấu thương mại', '521', '0'),
('5212', '', 'Hàng bán bị trả lại', '521', '0'),
('5213', '', 'Giảm giá hàng bán', '521', '0'),

('6111', '', 'Mua nguyên liệu, vật liệu', '611', '0'),
('6112', '', 'Mua hàng hóa', '611', '0'),

('621', '', 'Chi phí nguyên liệu, vật liệu trực tiếp', '621', '0'),

('622', '', 'Chi phí nhân công trực tiếp', '622', '0'),

('6231', '', 'Chi phí nhân công', '623', '0'),
('6232', '', 'Chi phí nguyên, vật liệu', '623', '0'),
('6233', '', 'Chi phí dụng cụ sản xuất', '623', '0'),
('6234', '', 'Chi phí khấu hao máy thi công', '623', '0'),
('6237', '', 'Chi phí dịch vụ mua ngoài', '623', '0'),
('6238', '', 'Chi phí bằng tiền khác', '623', '0'),

('6271', '', 'Chi phí nhân viên phân xưởng', '627', '0'),
('6272', '', 'Chi phí nguyên, vật liệu', '627', '0'),
('6273', '', 'Chi phí dụng cụ sản xuất', '627', '0'),
('6274', '', 'Chi phí khấu hao TSCĐ', '627', '0'),
('6277', '', 'Chi phí dịch vụ mua ngoài', '627', '0'),
('6278', '', 'Chi phí bằng tiền khác', '627', '0'),

('631', '', 'Giá thành sản xuất', '631', '0'),

('632', '', 'Giá vốn hàng bán', '632', '0'),

('635', '', 'Chi phí tài chính', '635', '0'),

('6411', '', 'Chi phí nhân viên', '641', '0'),
('6412', '', 'Chi phí nguyên vật liệu, bao bì', '641', '0'),
('6413', '', 'Chi phí dụng cụ, đồ dùng', '641', '0'),
('6414', '', 'Chi phí khấu hao TSCĐ', '641', '0'),
('6415', '', 'Chi phí bảo hành', '641', '0'),
('6417', '', 'Chi phí dịch vụ mua ngoài', '641', '0'),
('6418', '', 'Chi phí bằng tiền khác', '641', '0'),

('6421', '', 'Chi phí nhân viên quản lý', '642', '0'),
('6422', '', 'Chi phí vật liệu quản lý', '642', '0'),
('6423', '', 'Chi phí đồ dùng văn phòng', '642', '0'),
('6424', '', 'Chi phí khấu hao TSCĐ', '642', '0'),
('6425', '', 'Thuế, phí và lệ phí', '642', '0'),
('6426', '', 'Chi phí dự phòng', '642', '0'),
('6427', '', 'Chi phí dịch vụ mua ngoài', '642', '0'),
('6428', '', 'Chi phí bằng tiền khác', '642', '0'),

('711', '', 'Thu nhập khác', '711', '0'),

('811', '', 'Chi phí khác', '811', '0'),

('8211', '', 'Chi phí thuế TNDN hiện hành', '821', '0'),
('8212', '', 'Chi phí thuế TNDN hoãn lại', '821', '0'),

('911', '', 'Xác định kết quả kinh doanh', '911', '0');

-- Structure of table `0_chart_types` --

DROP TABLE IF EXISTS `0_chart_types`;

CREATE TABLE `0_chart_types` (
  `id` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `class_id` varchar(3) NOT NULL DEFAULT '',
  `parent` varchar(10) NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB;

-- Data of table `0_chart_types` --

INSERT INTO `0_chart_types` VALUES
('111', 'Tiền mặt', '1', '-1', 0),
('112', 'Tiền gửi Ngân hàng', '1', '-1', 0),
('113', 'Tiền đang chuyển', '1', '-1', 0),
('121', 'Chứng khoán kinh doanh', '1', '-1', 0),
('128', 'Đầu tư nắm giữ đến ngày đáo hạn', '1', '-1', 0),
('131', 'Phải thu của khách hàng', '1', '-1', 0),
('133', 'Thuế GTGT được khấu trừ', '1', '-1', 0),
('136', 'Phải thu nội bộ', '1', '-1', 0),
('138', 'Phải thu khác', '1', '-1', 0),
('141', 'Tạm ứng', '1', '-1', 0),
('151', 'Hàng mua đang đi đường', '1', '-1', 0),
('152', 'Nguyên liệu, vật liệu', '1', '-1', 0),
('153', 'Công cụ, dụng cụ', '1', '-1', 0),
('154', 'Chi phí sản xuất, kinh doanh dở dang', '1', '-1', 0),
('155', 'Thành phẩm', '1', '-1', 0),
('156', 'Hàng hóa', '1', '-1', 0),
('157', 'Hàng gửi đi bán', '1', '-1', 0),
('158', 'Hàng hoá kho bảo thuế', '1', '-1', 0),
('161', 'Chi sự nghiệp', '1', '-1', 0),
('171', 'Giao dịch mua bán lại trái phiếu chính phủ', '1', '-1', 0),

('211', 'Tài sản cố định hữu hình', '2', '-1', 0),
('212', 'Tài sản cố định thuê tài chính', '2', '-1', 0),
('213', 'Tài sản cố định vô hình', '2', '-1', 0),
('214', 'Hao mòn tài sản cố định', '2', '-1', 0),
('217', 'Bất động sản đầu tư', '2', '-1', 0),
('221', 'Đầu tư vào công ty con', '2', '-1', 0),
('222', 'Đầu tư vào công ty liên doanh, liên kết', '2', '-1', 0),
('228', 'Đầu tư khác', '2', '-1', 0),
('229', 'Dự phòng tổn thất tài sản', '2', '-1', 0),
('241', 'Xây dựng cơ bản dở dang', '2', '-1', 0),
('242', 'Chi phí trả trước', '2', '-1', 0),
('243', 'Tài sản thuế thu nhập hoãn lại', '2', '-1', 0),
('244', 'Cầm cố, thế chấp, ký quỹ, ký cược', '2', '-1', 0),

('331', 'Phải trả cho người bán', '3', '-1', 0),
('333', 'Thuế và các khoản phải nộp Nhà nước', '3', '-1', 0),
('3331', 'Thuế giá trị gia tăng phải nộp', '3', '333', 0),
('3338', 'Thuế bảo vệ môi trường và các loại thuế khác', '3', '333', 0),
('334', 'Phải trả người lao động', '3', '-1', 0),
('335', 'Chi phí phải trả', '3', '-1', 0),
('336', 'Phải trả nội bộ', '3', '-1', 0),
('337', 'Thanh toán theo tiến độ kế hoạch hợp đồng xây dựng', '3', '-1', 0),
('338', 'Phải trả, phải nộp khác', '3', '-1', 0),
('341', 'Vay và nợ thuê tài chính', '3', '-1', 0),
('343', 'Trái phiếu phát hành', '3', '-1', 0),
('3431', 'Trái phiếu thường', '3', '343', 0),
('344', 'Nhận ký quỹ, ký cược', '3', '-1', 0),
('347', 'Thuế thu nhập hoãn lại phải trả', '3', '-1', 0),
('352', 'Dự phòng phải trả', '3', '-1', 0),
('353', 'Quỹ khen thưởng phúc lợi', '3', '-1', 0),
('356', 'Quỹ phát triển khoa học và công nghệ', '3', '-1', 0),
('357', 'Quỹ bình ổn giá', '3', '-1', 0),

('411', 'Vốn đầu tư của chủ sở hữu', '4', '-1', 0),
('4111', 'Vốn góp của chủ sở hữu', '4', '411', 0),
('412', 'Chênh lệch đánh giá lại tài sản', '4', '-1', 0),
('413', 'Chênh lệch tỷ giá hối đoái', '4', '-1', 0),
('414', 'Quỹ đầu tư phát triển', '4', '-1', 0),
('417', 'Quỹ hỗ trợ sắp xếp doanh nghiệp', '4', '-1', 0),
('418', 'Các quỹ khác thuộc vốn chủ sở hữu', '4', '-1', 0),
('419', 'Cổ phiếu quỹ', '4', '-1', 0),
('421', 'Lợi nhuận sau thuế chưa phân phối', '4', '-1', 0),
('441', 'Nguồn vốn đầu tư xây dựng cơ bản', '4', '-1', 0),
('461', 'Nguồn kinh phí sự nghiệp', '4', '-1', 0),
('466', 'Nguồn kinh phí đã hình thành TSCĐ', '4', '-1', 0),

('511', 'Doanh thu bán hàng và cung cấp dịch vụ', '5', '-1', 0),
('515', 'Doanh thu hoạt động tài chính', '5', '-1', 0),
('521', 'Các khoản giảm trừ doanh thu', '5', '-1', 0),

('611', 'Mua hàng', '6', '-1', 0),
('621', 'Chi phí nguyên liệu, vật liệu trực tiếp', '6', '-1', 0),
('622', 'Chi phí nhân công trực tiếp', '6', '-1', 0),
('623', 'Chi phí sử dụng máy thi công', '6', '-1', 0),
('627', 'Chi phí sản xuất chung', '6', '-1', 0),
('631', 'Giá thành sản xuất', '6', '-1', 0),
('632', 'Giá vốn hàng bán', '6', '-1', 0),
('635', 'Chi phí tài chính', '6', '-1', 0),
('641', 'Chi phí bán hàng', '6', '-1', 0),
('642', 'Chi phí quản lý doanh nghiệp', '6', '-1', 0),

('711', 'Thu nhập khác', '7', '-1', 0),

('811', 'Chi phí khác', '8', '-1', 0),
('821', 'Chi phí thuế thu nhập doanh nghiệp', '8', '-1', 0),

('911', 'Xác định kết quả kinh doanh', '9', '-1', 0);

-- Structure of table `0_comments` --

DROP TABLE IF EXISTS `0_comments`;

CREATE TABLE `0_comments` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext,
  UNIQUE KEY `type_id_date` (`type`,`id`,`date_`),
  KEY `type_and_id` (`type`,`id`)
) ENGINE=InnoDB;

-- Data of table `0_comments` --

-- Structure of table `0_credit_status` --

DROP TABLE IF EXISTS `0_credit_status`;

CREATE TABLE `0_credit_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_description` char(100) NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_description` (`reason_description`)
) ENGINE=InnoDB AUTO_INCREMENT=5 ;

-- Data of table `0_credit_status` --

INSERT INTO `0_credit_status` VALUES
('1', 'Thanh toán tốt', '0', '0'),
('3', 'Đã từng thanh toán chậm', '0', '0'),
('4', 'Lịch sử thanh toán xấu', '1', '0');

-- Structure of table `0_crm_categories` --

DROP TABLE IF EXISTS `0_crm_categories`;

CREATE TABLE `0_crm_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pure technical key',
  `type` varchar(20) NOT NULL COMMENT 'contact type e.g. customer',
  `action` varchar(20) NOT NULL COMMENT 'detailed usage e.g. department',
  `name` varchar(30) NOT NULL COMMENT 'for category selector',
  `description` tinytext NOT NULL COMMENT 'usage description',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'nonzero for core system usage',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`action`),
  UNIQUE KEY `type_2` (`type`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 ;

-- Data of table `0_crm_categories` --

INSERT INTO `0_crm_categories` VALUES
('1', 'cust_branch', 'general', 'Chung', 'Thông tin chung liên hệ chi nhánh khách hàng (ghi đè cài đặt công ty)', '1', '0'),
('2', 'cust_branch', 'invoice', 'Hoá đơn', 'Lập hoá đơn (ghi đè cài đặt công ty)', '1', '0'),
('3', 'cust_branch', 'order', 'Đơn hàng', 'Xác nhận đơn hàng (ghi đè cài đặt công ty)', '1', '0'),
('4', 'cust_branch', 'delivery', 'Giao hàng', 'Thu xếp giao hàng (ghi đè cài đặt công ty)', '1', '0'),
('5', 'customer', 'general', 'Chung', 'Thông tin liên hệ chung của khách', '1', '0'),
('6', 'customer', 'order', 'Đơn hàng', 'Xác nhận đơn hàng', '1', '0'),
('7', 'customer', 'delivery', 'Giao hàng', 'Thu xếp giao hàng', '1', '0'),
('8', 'customer', 'invoice', 'Hoá đơn', 'Lập hoá đơn', '1', '0'),
('9', 'supplier', 'general', 'Chung', 'Thông tin chung liên hệ nhà cung cấp', '1', '0'),
('10', 'supplier', 'order', 'Đơn hàng', 'Thông tin đơn hàng', '1', '0'),
('11', 'supplier', 'delivery', 'Giao hàng', 'Thu xếp giao hàng', '1', '0'),
('12', 'supplier', 'invoice', 'Hoá đơn', 'Lập hoá đơn', '1', '0');

-- Structure of table `0_crm_contacts` --

DROP TABLE IF EXISTS `0_crm_contacts`;

CREATE TABLE `0_crm_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL DEFAULT '0' COMMENT 'foreign key to crm_persons',
  `type` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `action` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `entity_id` varchar(11) DEFAULT NULL COMMENT 'entity id in related class table',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`action`)
) ENGINE=InnoDB;

-- Data of table `0_crm_contacts` --

-- Structure of table `0_crm_persons` --

DROP TABLE IF EXISTS `0_crm_persons`;

CREATE TABLE `0_crm_persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(30) NOT NULL,
  `name` varchar(60) NOT NULL,
  `name2` varchar(60) DEFAULT NULL,
  `address` tinytext,
  `phone` varchar(30) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lang` char(5) DEFAULT NULL,
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB;

-- Data of table `0_crm_persons` --

-- Structure of table `0_currencies` --

DROP TABLE IF EXISTS `0_currencies`;

CREATE TABLE `0_currencies` (
  `currency` varchar(60) NOT NULL DEFAULT '',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_abrev`)
) ENGINE=InnoDB;

-- Data of table `0_currencies` --

INSERT INTO `0_currencies` VALUES
('US Dollars', 'USD', '$', 'United States', 'Cents', '1', '0'),
('CA Dollars', 'CAD', '$', 'Canada', 'Cents', '1', '0'),
('Euro', 'EUR', '€', 'Europe', 'Cents', '1', '0'),
('Pounds', 'GBP', '£', 'England', 'Pence', '1', '0'),
('Đồng Việt Nam', 'VND', 'đ', 'Vietnam', 'xu', '1', '0'),
('人民币', 'CNY', '¥', 'China', '¥', '092', '0');

-- Structure of table `0_cust_allocations` --

DROP TABLE IF EXISTS `0_cust_allocations`;

CREATE TABLE `0_cust_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trans_type_from` (`person_id`,`trans_type_from`,`trans_no_from`,`trans_type_to`,`trans_no_to`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB;

-- Data of table `0_cust_allocations` --

-- Structure of table `0_cust_branch` --

DROP TABLE IF EXISTS `0_cust_branch`;

CREATE TABLE `0_cust_branch` (
  `branch_code` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `br_name` varchar(60) NOT NULL DEFAULT '',
  `branch_ref` varchar(30) NOT NULL DEFAULT '',
  `br_address` tinytext NOT NULL,
  `area` int(11) DEFAULT NULL,
  `salesman` int(11) NOT NULL DEFAULT '0',
  `default_location` varchar(5) NOT NULL DEFAULT '',
  `tax_group_id` int(11) DEFAULT NULL,
  `sales_account` varchar(15) NOT NULL DEFAULT '',
  `sales_discount_account` varchar(15) NOT NULL DEFAULT '',
  `receivables_account` varchar(15) NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '',
  `default_ship_via` int(11) NOT NULL DEFAULT '1',
  `br_post_address` tinytext NOT NULL,
  `group_no` int(11) NOT NULL DEFAULT '0',
  `notes` tinytext NOT NULL,
  `bank_account` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`branch_code`,`debtor_no`),
  KEY `branch_ref` (`branch_ref`),
  KEY `group_no` (`group_no`)
) ENGINE=InnoDB;

-- Data of table `0_cust_branch` --

-- Structure of table `0_debtor_trans` --

DROP TABLE IF EXISTS `0_debtor_trans`;

CREATE TABLE `0_debtor_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `debtor_no` int(11) unsigned NOT NULL,
  `branch_code` int(11) NOT NULL DEFAULT '-1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` varchar(60) NOT NULL DEFAULT '',
  `tpe` int(11) NOT NULL DEFAULT '0',
  `order_` int(11) NOT NULL DEFAULT '0',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `ov_freight` double NOT NULL DEFAULT '0',
  `ov_freight_tax` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `prep_amount` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ship_via` int(11) DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`trans_no`,`debtor_no`),
  KEY `debtor_no` (`debtor_no`,`branch_code`),
  KEY `tran_date` (`tran_date`),
  KEY `order_` (`order_`)
) ENGINE=InnoDB;

-- Data of table `0_debtor_trans` --

-- Structure of table `0_debtor_trans_details` --

DROP TABLE IF EXISTS `0_debtor_trans_details`;

CREATE TABLE `0_debtor_trans_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_trans_no` int(11) DEFAULT NULL,
  `debtor_trans_type` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `qty_done` double NOT NULL DEFAULT '0',
  `src_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Transaction` (`debtor_trans_type`,`debtor_trans_no`),
  KEY `src_id` (`src_id`)
) ENGINE=InnoDB;

-- Data of table `0_debtor_trans_details` --

-- Structure of table `0_debtors_master` --

DROP TABLE IF EXISTS `0_debtors_master`;

CREATE TABLE `0_debtors_master` (
  `debtor_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) NOT NULL,
  `address` tinytext,
  `tax_id` varchar(55) NOT NULL DEFAULT '',
  `curr_code` char(3) NOT NULL DEFAULT '',
  `sales_type` int(11) NOT NULL DEFAULT '1',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `credit_status` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pymt_discount` double NOT NULL DEFAULT '0',
  `credit_limit` float NOT NULL DEFAULT '1000',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtor_no`),
  UNIQUE KEY `debtor_ref` (`debtor_ref`),
  KEY `name` (`name`)
) ENGINE=InnoDB;

-- Data of table `0_debtors_master` --

-- Structure of table `0_dimensions` --

DROP TABLE IF EXISTS `0_dimensions`;

CREATE TABLE `0_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `date_` (`date_`),
  KEY `due_date` (`due_date`),
  KEY `type_` (`type_`)
) ENGINE=InnoDB;

-- Data of table `0_dimensions` --

-- Structure of table `0_exchange_rates` --

DROP TABLE IF EXISTS `0_exchange_rates`;

CREATE TABLE `0_exchange_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curr_code` char(3) NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT '0',
  `rate_sell` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `curr_code` (`curr_code`,`date_`)
) ENGINE=InnoDB;

-- Data of table `0_exchange_rates` --

-- Structure of table `0_fiscal_year` --

DROP TABLE IF EXISTS `0_fiscal_year`;

CREATE TABLE `0_fiscal_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `begin` (`begin`),
  UNIQUE KEY `end` (`end`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_fiscal_year` --

INSERT INTO `0_fiscal_year` VALUES
('1', '2021-01-01', '2021-12-31', '1');

-- Structure of table `0_gl_trans` --

DROP TABLE IF EXISTS `0_gl_trans`;

CREATE TABLE `0_gl_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`),
  KEY `dimension_id` (`dimension_id`),
  KEY `dimension2_id` (`dimension2_id`),
  KEY `tran_date` (`tran_date`),
  KEY `account_and_tran_date` (`account`,`tran_date`)
) ENGINE=InnoDB;

-- Data of table `0_gl_trans` --

-- Structure of table `0_grn_batch` --

DROP TABLE IF EXISTS `0_grn_batch`;

CREATE TABLE `0_grn_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) DEFAULT NULL,
  `rate` double DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `delivery_date` (`delivery_date`),
  KEY `purch_order_no` (`purch_order_no`)
) ENGINE=InnoDB;

-- Data of table `0_grn_batch` --

-- Structure of table `0_grn_items` --

DROP TABLE IF EXISTS `0_grn_items`;

CREATE TABLE `0_grn_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_recd` double NOT NULL DEFAULT '0',
  `quantity_inv` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `grn_batch_id` (`grn_batch_id`)
) ENGINE=InnoDB;

-- Data of table `0_grn_items` --

-- Structure of table `0_groups` --

DROP TABLE IF EXISTS `0_groups`;

CREATE TABLE `0_groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=4 ;

-- Data of table `0_groups` --

INSERT INTO `0_groups` VALUES
('1', 'Nhỏ', '0'),
('2', 'Trung bình', '0'),
('3', 'Lớn', '0');

-- Structure of table `0_item_codes` --

DROP TABLE IF EXISTS `0_item_codes`;

CREATE TABLE `0_item_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(20) NOT NULL,
  `stock_id` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `category_id` smallint(6) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `is_foreign` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_id` (`stock_id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB;

-- Data of table `0_item_codes` --

-- Structure of table `0_item_tax_type_exemptions` --

DROP TABLE IF EXISTS `0_item_tax_type_exemptions`;

CREATE TABLE `0_item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_tax_type_id`,`tax_type_id`)
) ENGINE=InnoDB;

-- Data of table `0_item_tax_type_exemptions` --

-- Structure of table `0_item_tax_types` --

DROP TABLE IF EXISTS `0_item_tax_types`;

CREATE TABLE `0_item_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_item_tax_types` --

INSERT INTO `0_item_tax_types` VALUES
('1', 'Thông thường', '0', '0');

-- Structure of table `0_item_units` --

DROP TABLE IF EXISTS `0_item_units`;

CREATE TABLE `0_item_units` (
  `abbr` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `decimals` tinyint(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abbr`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB;

-- Data of table `0_item_units` --

INSERT INTO `0_item_units` VALUES
('bao', 'Bao', '0', '0'),
('bịch', 'Bịch', '0', '0'),
('bình', 'Bình', '0', '0'),
('bộ', 'Bộ', '0', '0'),
('cây', 'Cây', '0', '0'),
('chai', 'Chai', '0', '0'),
('chiếc', 'Chiếc', '0', '0'),
('chuyến', 'Chuyến', '0', '0'),
('con', 'Con', '0', '0'),
('cuộn', 'Cuộn', '0', '0'),
('giờ', 'Giờ', '0', '0'),
('gói', 'Gói', '0', '0'),
('hạt', 'Hạt', '0', '0'),
('hộp', 'Hộp', '0', '0'),
('kg', 'Kg', '0', '0'),
('km', 'Km', '0', '0'),
('lạng', 'Lạng', '0', '0'),
('lít', 'Lít', '0', '0'),
('lọ', 'Lọ', '0', '0'),
('máy', 'Máy', '0', '0'),
('m', 'Mét', '0', '0'),
('miếng', 'Miếng', '0', '0'),
('ngày', 'Ngày', '0', '0'),
('sợi', 'Sợi', '0', '0'),
('tấm', 'Tấm', '0', '0'),
('thùng', 'Thùng', '0', '0'),
('túi', 'Túi', '0', '0'),
('viên', 'Viên', '0', '0'),
('xe', 'Xe', '0', '0');

-- Structure of table `0_journal` --

DROP TABLE IF EXISTS `0_journal`;

CREATE TABLE `0_journal` (
  `type` smallint(6) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `tran_date` date DEFAULT '0000-00-00',
  `reference` varchar(60) NOT NULL DEFAULT '',
  `source_ref` varchar(60) NOT NULL DEFAULT '',
  `event_date` date DEFAULT '0000-00-00',
  `doc_date` date NOT NULL DEFAULT '0000-00-00',
  `currency` char(3) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`type`,`trans_no`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB;

-- Data of table `0_journal` --

-- Structure of table `0_loc_stock` --

DROP TABLE IF EXISTS `0_loc_stock`;

CREATE TABLE `0_loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `reorder_level` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB;

-- Data of table `0_loc_stock` --

-- Structure of table `0_locations` --

DROP TABLE IF EXISTS `0_locations`;

CREATE TABLE `0_locations` (
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `location_name` varchar(60) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `fixed_asset` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`)
) ENGINE=InnoDB;

-- Data of table `0_locations` --

INSERT INTO `0_locations` VALUES
('DEF', 'Mặc định', 'N/A', '', '', '', '', '', '0', '0');

-- Structure of table `0_payment_terms` --

DROP TABLE IF EXISTS `0_payment_terms`;

CREATE TABLE `0_payment_terms` (
  `terms_indicator` int(11) NOT NULL AUTO_INCREMENT,
  `terms` char(80) NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT '0',
  `day_in_following_month` smallint(6) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`terms_indicator`),
  UNIQUE KEY `terms` (`terms`)
) ENGINE=InnoDB AUTO_INCREMENT=6 ;

-- Data of table `0_payment_terms` --

INSERT INTO `0_payment_terms` VALUES
('1', 'Ngày 15 tháng sau', '0', '17', '0'),
('2', 'Cuối tháng', '0', '30', '0'),
('3', 'Sau 10 ngày', '10', '0', '0'),
('4', 'Tiền mặt', '0', '0', '0'),
('5', 'Trả trước', '-1', '0', '0');

-- Structure of table `0_prices` --

DROP TABLE IF EXISTS `0_prices`;

CREATE TABLE `0_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT '0',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`)
) ENGINE=InnoDB;

-- Data of table `0_prices` --

-- Structure of table `0_print_profiles` --

DROP TABLE IF EXISTS `0_print_profiles`;

CREATE TABLE `0_print_profiles` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(30) NOT NULL,
  `report` varchar(5) DEFAULT NULL,
  `printer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile` (`profile`,`report`)
) ENGINE=InnoDB AUTO_INCREMENT=10 ;

-- Data of table `0_print_profiles` --

INSERT INTO `0_print_profiles` VALUES
('1', 'Ngoài văn phòng', '', '0'),
('2', 'Bộ phận bán hàng', '', '0'),
('3', 'Trung tâm', '', '2'),
('4', 'Bộ phận bán hàng', '104', '2'),
('5', 'Bộ phận bán hàng', '105', '2'),
('6', 'Bộ phận bán hàng', '107', '2'),
('7', 'Bộ phận bán hàng', '109', '2'),
('8', 'Bộ phận bán hàng', '110', '2'),
('9', 'Bộ phận bán hàng', '201', '2');

-- Structure of table `0_printers` --

DROP TABLE IF EXISTS `0_printers`;

CREATE TABLE `0_printers` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(60) NOT NULL,
  `queue` varchar(20) NOT NULL,
  `host` varchar(40) NOT NULL,
  `port` smallint(11) unsigned NOT NULL,
  `timeout` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 ;

-- Data of table `0_printers` --

INSERT INTO `0_printers` VALUES
('1', 'QL500', 'Label printer', 'QL500', 'server', '127', '20'),
('2', 'Samsung', 'Main network printer', 'scx4521F', 'server', '515', '5'),
('3', 'Local', 'Local print server at user IP', 'lp', '', '515', '10');

-- Structure of table `0_purch_data` --

DROP TABLE IF EXISTS `0_purch_data`;

CREATE TABLE `0_purch_data` (
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(50) NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT '1',
  `supplier_description` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`stock_id`)
) ENGINE=InnoDB;

-- Data of table `0_purch_data` --

-- Structure of table `0_purch_order_details` --

DROP TABLE IF EXISTS `0_purch_order_details`;

CREATE TABLE `0_purch_order_details` (
  `po_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_detail_item`),
  KEY `order` (`order_no`,`po_detail_item`),
  KEY `itemcode` (`item_code`)
) ENGINE=InnoDB;

-- Data of table `0_purch_order_details` --

-- Structure of table `0_purch_orders` --

DROP TABLE IF EXISTS `0_purch_orders`;

CREATE TABLE `0_purch_orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `prep_amount` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_no`),
  KEY `ord_date` (`ord_date`)
) ENGINE=InnoDB;

-- Data of table `0_purch_orders` --

-- Structure of table `0_quick_entries` --

DROP TABLE IF EXISTS `0_quick_entries`;

CREATE TABLE `0_quick_entries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(60) NOT NULL,
  `usage` varchar(120) DEFAULT NULL,
  `base_amount` double NOT NULL DEFAULT '0',
  `base_desc` varchar(60) DEFAULT NULL,
  `bal_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=4 ;

-- Data of table `0_quick_entries` --

INSERT INTO `0_quick_entries` VALUES
('1', '1', 'Bảo trì', NULL, '0', 'Amount', '0'),
('2', '4', 'Cước điện thoại', NULL, '0', 'Amount', '0'),
('3', '2', 'Bán lẻ', 'Bán lẻ không hoá đơn', '0', 'Amount', '0');

-- Structure of table `0_quick_entry_lines` --

DROP TABLE IF EXISTS `0_quick_entry_lines`;

CREATE TABLE `0_quick_entry_lines` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `qid` smallint(6) unsigned NOT NULL,
  `amount` double DEFAULT '0',
  `memo` tinytext NOT NULL,
  `action` varchar(2) NOT NULL,
  `dest_id` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` smallint(6) unsigned DEFAULT NULL,
  `dimension2_id` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qid` (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 ;

-- Data of table `0_quick_entry_lines` --

INSERT INTO `0_quick_entry_lines` VALUES
('1', '1', '0', '', 't-', '1', '0', '0'),
('2', '2', '0', '', 't-', '1', '0', '0'),
('3', '3', '0', '', 't-', '1', '0', '0'),
('4', '3', '0', '', '=', '5111', '0', '0'),
('5', '1', '0', '', '=', '6277', '0', '0'),
('6', '2', '0', '', '=', '6427', '0', '0');

-- Structure of table `0_recurrent_invoices` --

DROP TABLE IF EXISTS `0_recurrent_invoices`;

CREATE TABLE `0_recurrent_invoices` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `order_no` int(11) unsigned NOT NULL,
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `group_no` smallint(6) unsigned DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `monthly` int(11) NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB;

-- Data of table `0_recurrent_invoices` --

-- Structure of table `0_reflines` --

DROP TABLE IF EXISTS `0_reflines`;

CREATE TABLE `0_reflines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` int(11) NOT NULL,
  `prefix` char(5) NOT NULL DEFAULT '',
  `pattern` varchar(35) NOT NULL DEFAULT '1',
  `description` varchar(60) NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`trans_type`, `prefix`)
) ENGINE=InnoDB AUTO_INCREMENT=23 ;

-- Data of table `0_reflines` --

INSERT INTO `0_reflines` VALUES
('1', '0', '', '{001}/{YYYY}', '', '1', '0'),
('2', '1', '', '{001}/{YYYY}', '', '1', '0'),
('3', '2', '', '{001}/{YYYY}', '', '1', '0'),
('4', '4', '', '{001}/{YYYY}', '', '1', '0'),
('5', '10', '', '{001}/{YYYY}', '', '1', '0'),
('6', '11', '', '{001}/{YYYY}', '', '1', '0'),
('7', '12', '', '{001}/{YYYY}', '', '1', '0'),
('8', '13', '', '{001}/{YYYY}', '', '1', '0'),
('9', '16', '', '{001}/{YYYY}', '', '1', '0'),
('10', '17', '', '{001}/{YYYY}', '', '1', '0'),
('11', '18', '', '{001}/{YYYY}', '', '1', '0'),
('12', '20', '', '{001}/{YYYY}', '', '1', '0'),
('13', '21', '', '{001}/{YYYY}', '', '1', '0'),
('14', '22', '', '{001}/{YYYY}', '', '1', '0'),
('15', '25', '', '{001}/{YYYY}', '', '1', '0'),
('16', '26', '', '{001}/{YYYY}', '', '1', '0'),
('17', '28', '', '{001}/{YYYY}', '', '1', '0'),
('18', '29', '', '{001}/{YYYY}', '', '1', '0'),
('19', '30', '', '{001}/{YYYY}', '', '1', '0'),
('20', '32', '', '{001}/{YYYY}', '', '1', '0'),
('21', '35', '', '{001}/{YYYY}', '', '1', '0'),
('22', '40', '', '{001}/{YYYY}', '', '1', '0');

-- Structure of table `0_refs` --

DROP TABLE IF EXISTS `0_refs`;

CREATE TABLE `0_refs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`),
  KEY `Type_and_Reference` (`type`,`reference`)
) ENGINE=InnoDB;

-- Data of table `0_refs` --

-- Structure of table `0_sales_order_details` --

DROP TABLE IF EXISTS `0_sales_order_details`;

CREATE TABLE `0_sales_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `stk_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_sent` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `invoiced` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sorder` (`trans_type`,`order_no`),
  KEY `stkcode` (`stk_code`)
) ENGINE=InnoDB;

-- Data of table `0_sales_order_details` --

-- Structure of table `0_sales_orders` --

DROP TABLE IF EXISTS `0_sales_orders`;

CREATE TABLE `0_sales_orders` (
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `branch_code` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `customer_ref` tinytext NOT NULL,
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `order_type` int(11) NOT NULL DEFAULT '0',
  `ship_via` int(11) NOT NULL DEFAULT '0',
  `delivery_address` tinytext NOT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `deliver_to` tinytext NOT NULL,
  `freight_cost` double NOT NULL DEFAULT '0',
  `from_stk_loc` varchar(5) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `payment_terms` int(11) DEFAULT NULL,
  `total` double NOT NULL DEFAULT '0',
  `prep_amount` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_type`,`order_no`)
) ENGINE=InnoDB;

-- Data of table `0_sales_orders` --

-- Structure of table `0_sales_pos` --

DROP TABLE IF EXISTS `0_sales_pos`;

CREATE TABLE `0_sales_pos` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(30) NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) NOT NULL,
  `pos_account` smallint(6) unsigned NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_name` (`pos_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_sales_pos` --

INSERT INTO `0_sales_pos` VALUES
('1', 'Mặc định', '1', '1', 'DEF', '2', '0');

-- Structure of table `0_sales_types` --

DROP TABLE IF EXISTS `0_sales_types`;

CREATE TABLE `0_sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` char(50) NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_type` (`sales_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 ;

-- Data of table `0_sales_types` --

INSERT INTO `0_sales_types` VALUES
('1', 'Bán lẻ', '1', '0', '0'),
('2', 'Bán buôn', '0', '0.7', '0');

-- Structure of table `0_salesman` --

DROP TABLE IF EXISTS `0_salesman`;

CREATE TABLE `0_salesman` (
  `salesman_code` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_name` char(60) NOT NULL DEFAULT '',
  `salesman_phone` char(30) NOT NULL DEFAULT '',
  `salesman_fax` char(30) NOT NULL DEFAULT '',
  `salesman_email` varchar(100) NOT NULL DEFAULT '',
  `provision` double NOT NULL DEFAULT '0',
  `break_pt` double NOT NULL DEFAULT '0',
  `provision2` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesman_code`),
  UNIQUE KEY `salesman_name` (`salesman_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_salesman` --

INSERT INTO `0_salesman` VALUES
('1', 'Người bán 1', '', '', '', '5', '1000', '4', '0');

-- Structure of table `0_security_roles` --

DROP TABLE IF EXISTS `0_security_roles`;

CREATE TABLE `0_security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `sections` text,
  `areas` text,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=11 ;

-- Data of table `0_security_roles` --

INSERT INTO `0_security_roles` VALUES
('1', 'Xem thông tin', 'Xem thông tin', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132;775', '0'),
('2', 'Quản trị hệ thống', 'Quản trị hệ thống', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;9472;9728;10496;10752;11008;13056;13312;15616;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;526;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;9217;9218;9220;9473;9474;9475;9476;9729;10497;10753;10754;10755;10756;10757;11009;11010;11011;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15628;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132;775', '0'),
('3', 'Bán hàng', 'Nhân viên bán hàng', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873;775', '0'),
('4', 'Quản lý kho', 'Quản lý kho', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132;775', '0'),
('5', 'Quản lý sản xuất', 'Quản lý sản xuất', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', '0'),
('6', 'Quản lý thu mua', 'Quản lý thu mua', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', '0'),
('7', 'Kế toán phải thu', 'Kế toán phải thu', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132;775', '0'),
('8', 'Kế toán phải trả', 'Kế toán phải trả', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', '0'),
('9', 'Kế toán', 'Kế toán mới', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132;775', '0'),
('10', 'Quản lý', 'Quản lý chung', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132;775', '0');

-- Structure of table `0_shippers` --

DROP TABLE IF EXISTS `0_shippers`;

CREATE TABLE `0_shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_name` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `contact` tinytext NOT NULL,
  `address` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`),
  UNIQUE KEY `name` (`shipper_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_shippers` --

INSERT INTO `0_shippers` VALUES
('1', 'Cty vận chuyển', '', '', '', '', '0');

-- Structure of table `0_sql_trail` --

DROP TABLE IF EXISTS `0_sql_trail`;

CREATE TABLE `0_sql_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Data of table `0_sql_trail` --

-- Structure of table `0_stock_category` --

DROP TABLE IF EXISTS `0_stock_category`;

CREATE TABLE `0_stock_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `dflt_tax_type` int(11) NOT NULL DEFAULT '1',
  `dflt_units` varchar(20) NOT NULL DEFAULT 'each',
  `dflt_mb_flag` char(1) NOT NULL DEFAULT 'B',
  `dflt_sales_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_cogs_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_inventory_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_adjustment_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_wip_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_dim1` int(11) DEFAULT NULL,
  `dflt_dim2` int(11) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `dflt_no_sale` tinyint(1) NOT NULL DEFAULT '0',
  `dflt_no_purchase` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=5 ;

-- Data of table `0_stock_category` --

INSERT INTO `0_stock_category` VALUES
('1', 'Hàng hóa, vật liệu', '1', 'chiếc', 'B', '5111', '632', '1561', '632', '154', '0', '0', '0', '0', '0'),
('2', 'Dụng cụ, máy móc', '1', 'chiếc', 'B', '5111', '632', '1561', '632', '154', '0', '0', '0', '0', '0'),
('3', 'Gói sản phẩm, dịch vụ', '1', 'bộ', 'M', '5111', '632', '1561', '632', '154', '0', '0', '0', '0', '0'),
('4', 'Dịch vụ', '1', 'giờ', 'D', '5111', '632', '1561', '632', '154', '0', '0', '0', '0', '0');

-- Structure of table `0_stock_fa_class` --

DROP TABLE IF EXISTS `0_stock_fa_class`;

CREATE TABLE `0_stock_fa_class` (
  `fa_class_id` varchar(20) NOT NULL DEFAULT '',
  `parent_id` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `depreciation_rate` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fa_class_id`)
) ENGINE=InnoDB;

-- Data of table `0_stock_fa_class` --

-- Structure of table `0_stock_master` --

DROP TABLE IF EXISTS `0_stock_master`;

CREATE TABLE `0_stock_master` (
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mb_flag` char(1) NOT NULL DEFAULT 'B',
  `sales_account` varchar(15) NOT NULL DEFAULT '',
  `cogs_account` varchar(15) NOT NULL DEFAULT '',
  `inventory_account` varchar(15) NOT NULL DEFAULT '',
  `adjustment_account` varchar(15) NOT NULL DEFAULT '',
  `wip_account` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `purchase_cost` double NOT NULL DEFAULT '0',
  `material_cost` double NOT NULL DEFAULT '0',
  `labour_cost` double NOT NULL DEFAULT '0',
  `overhead_cost` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `no_sale` tinyint(1) NOT NULL DEFAULT '0',
  `no_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `depreciation_method` char(1) NOT NULL DEFAULT 'S',
  `depreciation_rate` double NOT NULL DEFAULT '0',
  `depreciation_factor` double NOT NULL DEFAULT '1',
  `depreciation_start` date NOT NULL DEFAULT '0000-00-00',
  `depreciation_date` date NOT NULL DEFAULT '0000-00-00',
  `fa_class_id` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB;

-- Data of table `0_stock_master` --

-- Structure of table `0_stock_moves` --

DROP TABLE IF EXISTS `0_stock_moves`;

CREATE TABLE `0_stock_moves` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `price` double NOT NULL DEFAULT '0',
  `reference` char(40) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `standard_cost` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `type` (`type`,`trans_no`),
  KEY `Move` (`stock_id`,`loc_code`,`tran_date`)
) ENGINE=InnoDB;

-- Data of table `0_stock_moves` --

-- Structure of table `0_supp_allocations` --

DROP TABLE IF EXISTS `0_supp_allocations`;

CREATE TABLE `0_supp_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trans_type_from` (`person_id`,`trans_type_from`,`trans_no_from`,`trans_type_to`,`trans_no_to`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB;

-- Data of table `0_supp_allocations` --

-- Structure of table `0_supp_invoice_items` --

DROP TABLE IF EXISTS `0_supp_invoice_items`;

CREATE TABLE `0_supp_invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_trans_no` int(11) DEFAULT NULL,
  `supp_trans_type` int(11) DEFAULT NULL,
  `gl_code` varchar(15) NOT NULL DEFAULT '',
  `grn_item_id` int(11) DEFAULT NULL,
  `po_detail_item_id` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `quantity` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `memo_` tinytext,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Transaction` (`supp_trans_type`,`supp_trans_no`,`stock_id`)
) ENGINE=InnoDB;

-- Data of table `0_supp_invoice_items` --

-- Structure of table `0_supp_trans` --

DROP TABLE IF EXISTS `0_supp_trans`;

CREATE TABLE `0_supp_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `supplier_id` int(11) unsigned NOT NULL,
  `reference` tinytext NOT NULL,
  `supp_reference` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `alloc` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`trans_no`,`supplier_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB;

-- Data of table `0_supp_trans` --

-- Structure of table `0_suppliers` --

DROP TABLE IF EXISTS `0_suppliers`;

CREATE TABLE `0_suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(60) NOT NULL DEFAULT '',
  `supp_ref` varchar(30) NOT NULL DEFAULT '',
  `address` tinytext NOT NULL,
  `supp_address` tinytext NOT NULL,
  `gst_no` varchar(25) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `supp_account_no` varchar(40) NOT NULL DEFAULT '',
  `website` varchar(100) NOT NULL DEFAULT '',
  `bank_account` varchar(60) NOT NULL DEFAULT '',
  `curr_code` char(3) DEFAULT NULL,
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `tax_group_id` int(11) DEFAULT NULL,
  `credit_limit` double NOT NULL DEFAULT '0',
  `purchase_account` varchar(15) NOT NULL DEFAULT '',
  `payable_account` varchar(15) NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplier_id`),
  KEY `supp_ref` (`supp_ref`)
) ENGINE=InnoDB;

-- Data of table `0_suppliers` --

-- Structure of table `0_sys_prefs` --

DROP TABLE IF EXISTS `0_sys_prefs`;

CREATE TABLE `0_sys_prefs` (
  `name` varchar(35) NOT NULL DEFAULT '',
  `category` varchar(30) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `length` smallint(6) DEFAULT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`),
  KEY `category` (`category`)
) ENGINE=InnoDB;

-- Data of table `0_sys_prefs` --

INSERT INTO `0_sys_prefs` VALUES
('coy_name', 'setup.company', 'varchar', 60, 'Company name'),
('gst_no', 'setup.company', 'varchar', 25, ''),
('coy_no', 'setup.company', 'varchar', 25, ''),
('tax_prd', 'setup.company', 'int', 11, '1'),
('tax_last', 'setup.company', 'int', 11, '1'),
('postal_address', 'setup.company', 'tinytext', 0, 'N/A'),
('phone', 'setup.company', 'varchar', 30, ''),
('fax', 'setup.company', 'varchar', 30, ''),
('email', 'setup.company', 'varchar', 100, ''),
('coy_logo', 'setup.company', 'varchar', 100, ''),
('domicile', 'setup.company', 'varchar', 55, ''),
('curr_default', 'setup.company', 'char', 3, 'VND'),
('use_dimension', 'setup.company', 'tinyint', 1, '1'),
('f_year', 'setup.company', 'int', 11, '1'),
('shortname_name_in_list','setup.company', 'tinyint', 1, '0'),
('no_item_list', 'setup.company', 'tinyint', 1, '0'),
('no_customer_list', 'setup.company', 'tinyint', 1, '0'),
('no_supplier_list', 'setup.company', 'tinyint', 1, '0'),
('base_sales', 'setup.company', 'int', 11, '1'),
('time_zone', 'setup.company', 'tinyint', 1, '0'),
('add_pct', 'setup.company', 'int', 5, '-1'),
('round_to', 'setup.company', 'int', 5, '1'),
('login_tout', 'setup.company', 'smallint', 6, '600'),
('past_due_days', 'glsetup.general', 'int', 11, '30'),
('profit_loss_year_act', 'glsetup.general', 'varchar', 15, '911'),
('retained_earnings_act', 'glsetup.general', 'varchar', 15, '4212'),
('bank_charge_act', 'glsetup.general', 'varchar', 15, '6428'),
('exchange_diff_act', 'glsetup.general', 'varchar', 15, '4131'),
('tax_algorithm', 'glsetup.customer', 'tinyint', 1, '1'),
('default_credit_limit', 'glsetup.customer', 'int', 11, '10000000'),
('accumulate_shipping', 'glsetup.customer', 'tinyint', 1, '0'),
('legal_text', 'glsetup.customer', 'tinytext', 0, ''),
('freight_act', 'glsetup.customer', 'varchar', 15, '6418'),
('debtors_act', 'glsetup.sales', 'varchar', 15, '131'),
('default_sales_act', 'glsetup.sales', 'varchar', 15, '5111'),
('default_sales_discount_act', 'glsetup.sales', 'varchar', 15, '5211'),
('default_prompt_payment_act', 'glsetup.sales', 'varchar', 15, '5211'),
('default_delivery_required', 'glsetup.sales', 'smallint', 6, '1'),
('default_receival_required', 'glsetup.purchase', 'smallint', 6, '10'),
('default_quote_valid_days', 'glsetup.sales', 'smallint', 6, '30'),
('default_dim_required', 'glsetup.dims', 'int', 11, '20'),
('pyt_discount_act', 'glsetup.purchase', 'varchar', 15, '515'),
('creditors_act', 'glsetup.purchase', 'varchar', 15, '331'),
('po_over_receive', 'glsetup.purchase', 'int', 11, '10'),
('po_over_charge', 'glsetup.purchase', 'int', 11, '10'),
('allow_negative_stock', 'glsetup.inventory', 'tinyint', 1, '0'),
('default_inventory_act', 'glsetup.items', 'varchar', 15, '1561'),
('default_cogs_act', 'glsetup.items', 'varchar', 15, '632'),
('default_adj_act', 'glsetup.items', 'varchar', 15, '632'),
('default_inv_sales_act', 'glsetup.items', 'varchar', 15, '5111'),
('default_wip_act', 'glsetup.items', 'varchar', 15, '154'),
('default_workorder_required', 'glsetup.manuf', 'int', 11, '20'),
('version_id', 'system', 'varchar', 11, '0.1'),
('auto_curr_reval', 'setup.company', 'smallint', 6, '1'),
('grn_clearing_act', 'glsetup.purchase', 'varchar', 15, '151'),
('bcc_email', 'setup.company', 'varchar', 100, ''),
('deferred_income_act', 'glsetup.sales', 'varchar', 15, '3387'),
('gl_closing_date','setup.closing_date', 'date', 8, ''),
('alternative_tax_include_on_docs','setup.company', 'tinyint', 1, '0'),
('no_zero_lines_amount','glsetup.sales', 'tinyint', 1, '1'),
('show_po_item_codes','glsetup.purchase', 'tinyint', 1, '0'),
('accounts_alpha','glsetup.general', 'tinyint', 1, '0'),
('loc_notification','glsetup.inventory', 'tinyint', 1, '0'),
('print_invoice_no','glsetup.sales', 'tinyint', 1, '0'),
('allow_negative_prices','glsetup.inventory', 'tinyint', 1, '1'),
('print_item_images_on_quote','glsetup.inventory', 'tinyint', 1, '0'),
('suppress_tax_rates','setup.company', 'tinyint', 1, '0'),
('company_logo_report','setup.company', 'tinyint', 1, '0'),
('barcodes_on_stock','setup.company', 'tinyint', 1, '0'),
('print_dialog_direct','setup.company', 'tinyint', 1, '0'),
('ref_no_auto_increase','setup.company', 'tinyint', 1, '0'),
('default_loss_on_asset_disposal_act', 'glsetup.items', 'varchar', '15', '6274'),
('depreciation_period', 'glsetup.company', 'tinyint', '1', '1'),
('use_manufacturing','setup.company', 'tinyint', 1, '1'),
('max_days_in_docs','setup.company', 'smallint', 5, '180'),
('use_fixed_assets','setup.company', 'tinyint', 1, '1');

-- Structure of table `0_tag_associations` --

DROP TABLE IF EXISTS `0_tag_associations`;

CREATE TABLE `0_tag_associations` (
  `record_id` varchar(15) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`,`tag_id`)
) ENGINE=InnoDB;

-- Data of table `0_tag_associations` --

-- Structure of table `0_tags` --

DROP TABLE IF EXISTS `0_tags`;

CREATE TABLE `0_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=InnoDB;

-- Data of table `0_tags` --

-- Structure of table `0_tax_group_items` --

DROP TABLE IF EXISTS `0_tax_group_items`;

CREATE TABLE `0_tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_shipping` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_group_id`,`tax_type_id`)
) ENGINE=InnoDB;

-- Data of table `0_tax_group_items` --

INSERT INTO `0_tax_group_items` VALUES
('1', '1', '1');

-- Structure of table `0_tax_groups` --

DROP TABLE IF EXISTS `0_tax_groups`;

CREATE TABLE `0_tax_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 ;

-- Data of table `0_tax_groups` --

INSERT INTO `0_tax_groups` VALUES
('1', 'Thuế', '0'),
('2', 'Không thuế', '0');

-- Structure of table `0_tax_types` --

DROP TABLE IF EXISTS `0_tax_types`;

CREATE TABLE `0_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` double NOT NULL DEFAULT '0',
  `sales_gl_code` varchar(15) NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_tax_types` --

INSERT INTO `0_tax_types` VALUES
('1', '10', '33311', '1331', 'Thuế', '0');

-- Structure of table `0_trans_tax_details` --

DROP TABLE IF EXISTS `0_trans_tax_details`;

CREATE TABLE `0_trans_tax_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `tran_date` date NOT NULL,
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ex_rate` double NOT NULL DEFAULT '1',
  `included_in_price` tinyint(1) NOT NULL DEFAULT '0',
  `net_amount` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `memo` tinytext,
  `reg_type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Type_and_Number` (`trans_type`,`trans_no`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB;

-- Data of table `0_trans_tax_details` --

-- Structure of table `0_useronline` --

DROP TABLE IF EXISTS `0_useronline`;

CREATE TABLE `0_useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB;

-- Data of table `0_useronline` --


-- Structure of table `0_users` --

DROP TABLE IF EXISTS `0_users`;

CREATE TABLE `0_users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `real_name` varchar(100) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `date_format` tinyint(1) NOT NULL DEFAULT '0',
  `date_sep` tinyint(1) NOT NULL DEFAULT '0',
  `tho_sep` tinyint(1) NOT NULL DEFAULT '0',
  `dec_sep` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) NOT NULL DEFAULT 'default',
  `page_size` varchar(20) NOT NULL DEFAULT 'A4',
  `prices_dec` smallint(6) NOT NULL DEFAULT '2',
  `qty_dec` smallint(6) NOT NULL DEFAULT '2',
  `rates_dec` smallint(6) NOT NULL DEFAULT '4',
  `percent_dec` smallint(6) NOT NULL DEFAULT '1',
  `show_gl` tinyint(1) NOT NULL DEFAULT '1',
  `show_codes` tinyint(1) NOT NULL DEFAULT '0',
  `show_hints` tinyint(1) NOT NULL DEFAULT '0',
  `last_visit_date` datetime DEFAULT NULL,
  `query_size` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `graphic_links` tinyint(1) DEFAULT '1',
  `pos` smallint(6) DEFAULT '1',
  `print_profile` varchar(30) NOT NULL DEFAULT '',
  `rep_popup` tinyint(1) DEFAULT '1',
  `sticky_doc_date` tinyint(1) DEFAULT '0',
  `startup_tab` varchar(20) NOT NULL DEFAULT '',
  `transaction_days` smallint(6) NOT NULL DEFAULT '30',
  `save_report_selections` smallint(6) NOT NULL DEFAULT '0',
  `use_date_picker` tinyint(1) NOT NULL DEFAULT '1',
  `def_print_destination` tinyint(1) NOT NULL DEFAULT '0',
  `def_print_orientation` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_users` --

INSERT INTO `0_users` VALUES
('1', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', '2', '', 'adm@aodieu.com', 'vi_VN', '0', '0', '0', '0', 'default', 'Letter', '2', '2', '4', '1', '1', '0', '0', '2020-04-04 12:34:29', '10', '1', '1', '1', '1', '0', 'orders', '30', '0', '1', '0', '0', '0');

-- Structure of table `0_voided` --

DROP TABLE IF EXISTS `0_voided`;

CREATE TABLE `0_voided` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext NOT NULL,
  UNIQUE KEY `id` (`type`,`id`)
) ENGINE=InnoDB;

-- Data of table `0_voided` --


-- Structure of table `0_wo_costing` --

DROP TABLE IF EXISTS `0_wo_costing`;

CREATE TABLE `0_wo_costing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `cost_type` tinyint(1) NOT NULL DEFAULT '0',
  `trans_type` int(11) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Data of table `0_wo_costing` --

-- Structure of table `0_wo_issue_items` --

DROP TABLE IF EXISTS `0_wo_issue_items`;

CREATE TABLE `0_wo_issue_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(40) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  `unit_cost` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Data of table `0_wo_issue_items` --


-- Structure of table `0_wo_issues` --

DROP TABLE IF EXISTS `0_wo_issues`;

CREATE TABLE `0_wo_issues` (
  `issue_no` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`issue_no`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB;

-- Data of table `0_wo_issues` --

-- Structure of table `0_wo_manufacture` --

DROP TABLE IF EXISTS `0_wo_manufacture`;

CREATE TABLE `0_wo_manufacture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB;

-- Data of table `0_wo_manufacture` --

-- Structure of table `0_wo_requirements` --

DROP TABLE IF EXISTS `0_wo_requirements`;

CREATE TABLE `0_wo_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `workcentre` int(11) NOT NULL DEFAULT '0',
  `units_req` double NOT NULL DEFAULT '1',
  `unit_cost` double NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `units_issued` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB;

-- Data of table `0_wo_requirements` --

-- Structure of table `0_workcentres` --

DROP TABLE IF EXISTS `0_workcentres`;

CREATE TABLE `0_workcentres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `description` char(50) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

-- Data of table `0_workcentres` --

INSERT INTO `0_workcentres` VALUES
('1', 'Work Centre', '', '0');

-- Structure of table `0_workorders` --

DROP TABLE IF EXISTS `0_workorders`;

CREATE TABLE `0_workorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_ref` varchar(60) NOT NULL DEFAULT '',
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `units_reqd` double NOT NULL DEFAULT '1',
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required_by` date NOT NULL DEFAULT '0000-00-00',
  `released_date` date NOT NULL DEFAULT '0000-00-00',
  `units_issued` double NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `additional_costs` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wo_ref` (`wo_ref`)
) ENGINE=InnoDB;

-- Data of table `0_workorders` --