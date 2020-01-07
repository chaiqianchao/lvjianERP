-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-01-07 18:15:53
-- 服务器版本： 5.5.62-log
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenbuild`
--

-- --------------------------------------------------------

--
-- 表的结构 `green_administrators`
--

CREATE TABLE IF NOT EXISTS `green_administrators` (
  `staff_id` int(11) NOT NULL COMMENT 'ID',
  `staff_name` varchar(50) NOT NULL COMMENT '员工姓名',
  `administrators_name` varchar(50) NOT NULL COMMENT '用户名',
  `administrators_password` varchar(32) NOT NULL COMMENT '密码',
  `admin` varchar(11) NOT NULL DEFAULT '0' COMMENT '管理员身份',
  `enable` tinyint(1) DEFAULT '0' COMMENT '是否启用管理员',
  `staff_enable` int(20) NOT NULL DEFAULT '1' COMMENT '是否启用员工',
  `project_view` int(11) DEFAULT '0' COMMENT '查看工程',
  `contract_view` int(11) DEFAULT '0' COMMENT '查看合同',
  `ledger` int(11) DEFAULT '0' COMMENT '台账管理',
  `bid` int(11) DEFAULT '0' COMMENT '招投标项目',
  `adminstrator` int(11) DEFAULT '0' COMMENT '管理员列表',
  `staff` int(11) DEFAULT '0' COMMENT '员工信息',
  `jurisdiction` int(11) DEFAULT '0' COMMENT '权限管理',
  `customer` int(11) DEFAULT '0' COMMENT '客户总览',
  `project_value` int(11) DEFAULT '0' COMMENT '工程产值',
  `contract_amount_limit` int(11) NOT NULL DEFAULT '0' COMMENT '工程产值中主体合同额',
  `department_value` int(11) DEFAULT '0' COMMENT '院产值',
  `staff_value` int(11) DEFAULT '0' COMMENT '个人产值',
  `invoice_new` int(11) DEFAULT '0' COMMENT '新增开票',
  `administrators_lastTime` int(11) DEFAULT NULL COMMENT '上次登录时间',
  `update_time` varchar(30) NOT NULL,
  `admin_status` int(11) NOT NULL DEFAULT '0' COMMENT '登录状态'
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `green_administrators`
--

INSERT INTO `green_administrators` (`staff_id`, `staff_name`, `administrators_name`, `administrators_password`, `admin`, `enable`, `staff_enable`, `project_view`, `contract_view`, `ledger`, `bid`, `adminstrator`, `staff`, `jurisdiction`, `customer`, `project_value`, `contract_amount_limit`, `department_value`, `staff_value`, `invoice_new`, `administrators_lastTime`, `update_time`, `admin_status`) VALUES
(3, '超级管理员', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0, 2, 2, 2, 1577067186, '1569673103', 1),
(25, '王宇飞', '13957728886', 'e10adc3949ba59abbe56e057f20f883e', '0', 0, 1, 1, 1, 1, 1, 0, 1, 0, 2, 2, 0, 2, 0, 0, 1575966361, '00:00:00', 0),
(45, '林娜娜', '13566181357', 'e10adc3949ba59abbe56e057f20f883e', '0', 0, 1, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 1575621328, '00:00:00', 0),
(46, '陈 赛', '13967715372', 'e10adc3949ba59abbe56e057f20f883e', '0', 0, 1, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 1575621390, '00:00:00', 0),
(91, '马静受', '13957723637', 'e10adc3949ba59abbe56e057f20f883e', '普通管理员', 1, 1, 2, 2, 0, 0, 2, 2, 2, 2, 2, 0, 0, 0, 0, 1577259978, '00:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `green_architecture_fee`
--

CREATE TABLE IF NOT EXISTS `green_architecture_fee` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_classification` varchar(100) NOT NULL COMMENT '类型',
  `design_unit_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '设计单价(元/平方米)',
  `unitprice_remarks` varchar(100) NOT NULL COMMENT '建筑备注'
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_architecture_fee`
--

INSERT INTO `green_architecture_fee` (`id`, `project_classification`, `design_unit_price`, `unitprice_remarks`) VALUES
(1, '别墅联排', 5.00, '不少于3500元/款'),
(2, '多层住宅', 1.80, '不少于4500元/款'),
(3, '高层住宅', 1.55, '不少于5500元/款'),
(4, '商业', 2.50, ''),
(5, '办公楼', 2.00, ''),
(6, '幼儿园', 3.00, '每所不少于6000元'),
(7, '学校（除宿舍外）', 2.50, '特殊单体另行商议'),
(8, '文体医疗', 3.00, ''),
(9, '工业', 0.70, ''),
(10, '有人防地下室', 1.20, ''),
(11, '无人防地下室', 1.50, ''),
(12, '修改图', 0.00, '特殊情况，手输价格'),
(13, '零星工程', 0.00, '特殊情况，手输价格');

-- --------------------------------------------------------

--
-- 表的结构 `green_bid`
--

CREATE TABLE IF NOT EXISTS `green_bid` (
  `toubiao_id` varchar(30) NOT NULL COMMENT '投标号',
  `project_name` varchar(200) NOT NULL COMMENT '项目名称',
  `bid_content` varchar(30) NOT NULL COMMENT '招标内容',
  `biaolan_price` varchar(20) DEFAULT NULL COMMENT '标栏价',
  `notice` varchar(50) DEFAULT NULL COMMENT '注意事项',
  `bid_pretrial_date` varchar(20) DEFAULT NULL COMMENT '资格预审时间',
  `bid_ispretrial` varchar(10) NOT NULL COMMENT '是否通过预审',
  `houshen_date` varchar(20) DEFAULT NULL COMMENT '资格后审时间',
  `question_date` varchar(20) DEFAULT NULL COMMENT '答疑时间',
  `bid_date` varchar(20) DEFAULT NULL COMMENT '交标时间',
  `bid_space` varchar(50) DEFAULT NULL COMMENT '交标地点',
  `bid_document` varchar(200) DEFAULT NULL COMMENT '交标资料',
  `bid_isbid` varchar(10) NOT NULL COMMENT '是否中标',
  `bid_master` varchar(20) DEFAULT NULL COMMENT '项目负责人',
  `join_person` varchar(140) DEFAULT NULL COMMENT '参与人员',
  `bid_progress` varchar(30) DEFAULT NULL COMMENT '进展阶段',
  `bid_type` varchar(10) NOT NULL COMMENT '投标类型',
  `toubiao_above` varchar(30) NOT NULL COMMENT '地上面积',
  `toubiao_under` varchar(30) NOT NULL COMMENT '地下面积',
  `toubiao_amount` varchar(30) NOT NULL COMMENT '投资额',
  `toubiao_address` varchar(30) NOT NULL COMMENT '地址',
  `toubiao_other` varchar(30) NOT NULL COMMENT '其他',
  `bid_deposite` varchar(11) DEFAULT NULL COMMENT '补偿费',
  `bid_compensation` varchar(11) DEFAULT NULL COMMENT '保证金',
  `bid_fabaoren` varchar(300) NOT NULL COMMENT '发包人',
  `bid_contractor_mobile` varchar(50) NOT NULL COMMENT '发包人联系方式',
  `bid_dailiren` varchar(50) NOT NULL COMMENT '代理人',
  `bid_agent_mobile` varchar(50) NOT NULL COMMENT '招标代理人联系方式',
  `bid_remarks` varchar(100) NOT NULL COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_bidcompensation`
--

CREATE TABLE IF NOT EXISTS `green_bidcompensation` (
  `id` int(10) NOT NULL,
  `toubiao_id` varchar(30) NOT NULL COMMENT '投标号',
  `compensation_price` varchar(20) DEFAULT NULL COMMENT '保证金',
  `compensation_invoice_date` varchar(20) DEFAULT NULL COMMENT '开票时间',
  `compensation_invoice_amount` varchar(20) DEFAULT NULL COMMENT '开票金额',
  `compensation_payment_date` varchar(20) DEFAULT NULL COMMENT '到账时间',
  `compensation_payment_amount` varchar(30) DEFAULT NULL COMMENT '到账金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='补偿费表';

-- --------------------------------------------------------

--
-- 表的结构 `green_biddeposite`
--

CREATE TABLE IF NOT EXISTS `green_biddeposite` (
  `id` int(10) NOT NULL,
  `toubiao_id` varchar(30) NOT NULL COMMENT '工程号',
  `deposite_invoice_id` varchar(20) DEFAULT NULL COMMENT '名次',
  `deposite_invoice_price` varchar(20) DEFAULT NULL COMMENT '补偿费',
  `deposite_invoice_object` varchar(200) DEFAULT NULL COMMENT '付款方',
  `deposite_invoice_date` varchar(20) DEFAULT NULL COMMENT '开票时间',
  `deposite_invoice_amount` varchar(30) DEFAULT NULL COMMENT '开票金额',
  `deposite_payment_date` varchar(20) DEFAULT NULL COMMENT '到账时间',
  `deposite_payment_amount` varchar(30) DEFAULT NULL COMMENT '到账金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='保证金表';

-- --------------------------------------------------------

--
-- 表的结构 `green_bidphase`
--

CREATE TABLE IF NOT EXISTS `green_bidphase` (
  `toubiao_id` varchar(30) NOT NULL COMMENT '工程号',
  `bidphase_phase1` date DEFAULT NULL COMMENT '预审完成',
  `bidphase_phase2` date DEFAULT NULL COMMENT '投标完成',
  `bidphase_phase3` date DEFAULT NULL COMMENT '公示完成',
  `bidphase_phase4` date DEFAULT NULL COMMENT '合同签订',
  `bidphase_phase5` date DEFAULT NULL COMMENT '已结算',
  `bidphase_phase6` date DEFAULT NULL COMMENT '已归档',
  `bidphase_phase7` varchar(50) DEFAULT NULL COMMENT '其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_confirm`
--

CREATE TABLE IF NOT EXISTS `green_confirm` (
  `confirm_id` int(11) NOT NULL COMMENT '发票编号',
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `invoice_date` varchar(20) NOT NULL COMMENT '开票日期',
  `invoice_amount` float(10,2) NOT NULL COMMENT '开票金额',
  `payment_date` varchar(20) NOT NULL COMMENT '到账日期',
  `payment_amount` float(10,2) NOT NULL COMMENT '到账金额',
  `confirm_drawer` varchar(100) NOT NULL DEFAULT '' COMMENT '开票人',
  `confirm_applicant` varchar(100) NOT NULL DEFAULT '' COMMENT '申请人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='收款确认表';

-- --------------------------------------------------------

--
-- 表的结构 `green_contract`
--

CREATE TABLE IF NOT EXISTS `green_contract` (
  `id` int(20) NOT NULL COMMENT '自增ID',
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `project_id` varchar(200) NOT NULL COMMENT '工程编号',
  `contract_type` varchar(140) NOT NULL COMMENT '合同分类',
  `contract_signtime` date DEFAULT NULL COMMENT '签订时间',
  `project_name` varchar(200) DEFAULT NULL COMMENT '工程名称',
  `contract_constructor` varchar(300) DEFAULT NULL COMMENT '建设方',
  `contract_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `contract_amount` varchar(11) DEFAULT NULL COMMENT '合同额',
  `contract_compute` varchar(20) DEFAULT NULL COMMENT '实算额',
  `contract_unitprice` varchar(11) DEFAULT NULL COMMENT '设计单价',
  `contract_design` varchar(100) DEFAULT NULL COMMENT '设计范围',
  `contract_content` varchar(140) DEFAULT NULL COMMENT '设计内容',
  `contract_phase` varchar(140) DEFAULT NULL COMMENT '设计阶段',
  `contract_reward` varchar(100) DEFAULT NULL COMMENT '奖罚措施',
  `contract_operator` varchar(100) DEFAULT NULL COMMENT '经办人',
  `contract_projectleader` varchar(100) DEFAULT NULL COMMENT '项目负责人',
  `contract_approver` varchar(100) DEFAULT NULL COMMENT '审批人',
  `project_totalarea` float(10,2) NOT NULL COMMENT '总建筑面积',
  `contract_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_contract`
--

INSERT INTO `green_contract` (`id`, `contract_id`, `project_id`, `contract_type`, `contract_signtime`, `project_name`, `contract_constructor`, `contract_agent`, `contract_amount`, `contract_compute`, `contract_unitprice`, `contract_design`, `contract_content`, `contract_phase`, `contract_reward`, `contract_operator`, `contract_projectleader`, `contract_approver`, `project_totalarea`, `contract_remarks`) VALUES
(21, '17-13', '17-13', '总院', '2017-10-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '1890000', '', NULL, '', '建筑,结构,给排水,暖通,电气,管网,六级人防', NULL, '', '马灵莉', ' 胡刚', '王宇飞', 99944.23, '');

-- --------------------------------------------------------

--
-- 表的结构 `green_contractaccountphase`
--

CREATE TABLE IF NOT EXISTS `green_contractaccountphase` (
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `contract_account_phase1` date DEFAULT NULL COMMENT '合同已签',
  `contract_account_phase2` date DEFAULT NULL COMMENT '方案',
  `contract_account_phase3` date DEFAULT NULL COMMENT '初步',
  `contract_account_phase4` date DEFAULT NULL COMMENT '施工图',
  `contract_account_phase5` date DEFAULT NULL COMMENT '审批',
  `contract_account_phase6` date DEFAULT NULL COMMENT '开工',
  `contract_account_phase7` date DEFAULT NULL COMMENT '交底',
  `contract_account_phase8` date DEFAULT NULL COMMENT '管网',
  `contract_account_phase9` date DEFAULT NULL COMMENT '基础完工',
  `contract_account_phase10` date DEFAULT NULL COMMENT '地下完工',
  `contract_account_phase11` date DEFAULT NULL COMMENT '结顶',
  `contract_account_phase12` date DEFAULT NULL COMMENT '室外工程',
  `contract_account_phase13` date DEFAULT NULL COMMENT '竣工验收',
  `contract_account_phase14` date DEFAULT NULL COMMENT '竣工备案'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_contractclassification`
--

CREATE TABLE IF NOT EXISTS `green_contractclassification` (
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `project_id` varchar(200) NOT NULL COMMENT '工程编号',
  `type1` int(11) NOT NULL COMMENT '总院',
  `type2` int(11) NOT NULL COMMENT '方案所',
  `type3` int(11) NOT NULL COMMENT '景观所',
  `type4` int(11) NOT NULL COMMENT '杭州分院',
  `type5` int(11) NOT NULL COMMENT '上海分院',
  `type6` int(11) NOT NULL COMMENT '工业化所',
  `type7` int(11) NOT NULL COMMENT '挂靠',
  `type8` int(11) NOT NULL COMMENT '财务',
  `type9` int(11) NOT NULL COMMENT '分包',
  `type10` int(11) NOT NULL COMMENT '备案',
  `type11` int(11) NOT NULL COMMENT '零星'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_contractconstruction`
--

CREATE TABLE IF NOT EXISTS `green_contractconstruction` (
  `contract_id` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '合同编号',
  `contract_design` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '设计内容',
  `contract_tome` date DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `green_contractledger`
--

CREATE TABLE IF NOT EXISTS `green_contractledger` (
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `contractledger_signtime` date DEFAULT NULL COMMENT '签订日期',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `project_constructor` varchar(300) DEFAULT NULL COMMENT '建设方',
  `project_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `contractledger_amount` varchar(20) DEFAULT NULL COMMENT '合同额',
  `contractledger_actual` varchar(20) DEFAULT NULL COMMENT '实算额',
  `contract_invoiced` varchar(30) DEFAULT NULL COMMENT '已开票金额',
  `contract_accepted` varchar(30) DEFAULT NULL COMMENT '已收款金额',
  `contract_acceptedratio` varchar(20) DEFAULT NULL COMMENT '实算额已收比例',
  `contract_unaccepted` varchar(30) DEFAULT NULL COMMENT '实算额未收款',
  `contract_invoiced_unpaid` varchar(30) DEFAULT NULL COMMENT '已开票未收款',
  `contract_value` varchar(30) DEFAULT NULL COMMENT '完成产值额',
  `contract_receivables` varchar(30) DEFAULT NULL COMMENT '应收款',
  `contractledger_remarks` varchar(100) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='合同台账表';

-- --------------------------------------------------------

--
-- 表的结构 `green_contractphase`
--

CREATE TABLE IF NOT EXISTS `green_contractphase` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `contract_phase1` varchar(20) DEFAULT NULL COMMENT '咨询',
  `contract_phase2` varchar(20) DEFAULT NULL COMMENT '强排',
  `contract_phase3` varchar(20) DEFAULT NULL COMMENT '方案',
  `contract_phase4` varchar(20) DEFAULT NULL COMMENT '初步',
  `contract_phase5` varchar(20) DEFAULT NULL COMMENT '施工图',
  `contract_phase6` varchar(20) DEFAULT NULL COMMENT '套图'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_contractphase`
--

INSERT INTO `green_contractphase` (`id`, `contract_id`, `project_id`, `contract_phase1`, `contract_phase2`, `contract_phase3`, `contract_phase4`, `contract_phase5`, `contract_phase6`) VALUES
(5, '17-13', '17-13', '未通过', '未通过', '未通过', '未通过', '未通过', '未通过');

-- --------------------------------------------------------

--
-- 表的结构 `green_contractsettlement`
--

CREATE TABLE IF NOT EXISTS `green_contractsettlement` (
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `contract_accepted` varchar(30) NOT NULL COMMENT '已收款金额',
  `contract_acceptedratio` varchar(30) NOT NULL COMMENT '实算额已收比例',
  `contract_unaccepted` varchar(30) NOT NULL COMMENT '实算额未收款',
  `contract_invoiced_unpaid` varchar(30) NOT NULL COMMENT '已开票未收款',
  `contract_value` varchar(30) NOT NULL COMMENT '完成产值额',
  `contract_receivables` varchar(30) NOT NULL COMMENT '应收款'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='合同结算表';

-- --------------------------------------------------------

--
-- 表的结构 `green_contractunitprice`
--

CREATE TABLE IF NOT EXISTS `green_contractunitprice` (
  `id` int(20) NOT NULL COMMENT '自增ID',
  `contract_id` varchar(30) NOT NULL COMMENT '合同编号',
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `contract_content` varchar(50) NOT NULL COMMENT '内容',
  `contract_unitprice` varchar(200) NOT NULL COMMENT '单价',
  `contract_floatingrate` varchar(50) NOT NULL COMMENT '下浮率',
  `contract_remarks` varchar(140) DEFAULT NULL COMMENT '说明'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_contractunitprice`
--

INSERT INTO `green_contractunitprice` (`id`, `contract_id`, `project_id`, `contract_content`, `contract_unitprice`, `contract_floatingrate`, `contract_remarks`) VALUES
(3, '17-13', '17-13', '主体设计', '19', '', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_customer`
--

CREATE TABLE IF NOT EXISTS `green_customer` (
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '项目名称',
  `customer_company` varchar(30) NOT NULL COMMENT '单位名称',
  `customer_department` varchar(30) NOT NULL COMMENT '部门',
  `customer_division` varchar(30) NOT NULL COMMENT '分工',
  `customer_name` varchar(30) NOT NULL COMMENT '姓名',
  `customer_position` varchar(30) NOT NULL COMMENT '职位',
  `customer_phone` varchar(20) NOT NULL COMMENT '电话',
  `customer_mobile` varchar(20) NOT NULL COMMENT '手机',
  `customer_wechat` varchar(20) NOT NULL COMMENT '微信',
  `customer_QQ` varchar(20) NOT NULL COMMENT 'QQ',
  `customer_email` varchar(30) NOT NULL COMMENT '邮箱',
  `customer_remarks` varchar(140) NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_departmentvalue`
--

CREATE TABLE IF NOT EXISTS `green_departmentvalue` (
  `id` int(11) NOT NULL,
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(20) NOT NULL COMMENT '人员姓名',
  `draw_date` date NOT NULL COMMENT '出图时间',
  `total_personalvalue` float(10,2) NOT NULL COMMENT '个人产值汇总',
  `reward_coefficient` float(10,2) NOT NULL COMMENT '年度奖惩调整系数',
  `special_allowance` float(10,2) NOT NULL COMMENT '特殊津贴',
  `yearend_personalvalue` float(10,2) NOT NULL COMMENT '年终个人产值',
  `departmentvalue_remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_departmentvalue`
--

INSERT INTO `green_departmentvalue` (`id`, `staff_department`, `staff_name`, `draw_date`, `total_personalvalue`, `reward_coefficient`, `special_allowance`, `yearend_personalvalue`, `departmentvalue_remarks`) VALUES
(5, '院长室', '王宇飞', '2019-11-14', 0.00, 1.00, 0.00, 0.00, ' '),
(4, '建筑二所', '陈赛', '2019-11-14', 0.00, 1.00, 0.00, 0.00, ' '),
(6, '院长室', '胡刚', '2019-11-14', 0.00, 1.00, 0.00, 0.00, ' ');

-- --------------------------------------------------------

--
-- 表的结构 `green_drawing_fee`
--

CREATE TABLE IF NOT EXISTS `green_drawing_fee` (
  `id` int(20) NOT NULL COMMENT '自增ID',
  `drawing_size` varchar(11) NOT NULL COMMENT '图纸尺寸',
  `drawing_blueprint` float(10,2) DEFAULT NULL COMMENT '蓝图 单价（元/张）',
  `drawing_sulphuric_acid_diagram` float(10,2) DEFAULT NULL COMMENT '硫酸图 单价（元/张）',
  `drawing_text_production` float(10,2) DEFAULT NULL COMMENT '文本制作 单价（元/张）'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='图纸家晒费计算表';

--
-- 转存表中的数据 `green_drawing_fee`
--

INSERT INTO `green_drawing_fee` (`id`, `drawing_size`, `drawing_blueprint`, `drawing_sulphuric_acid_diagram`, `drawing_text_production`) VALUES
(1, 'A0', 7.00, 20.00, 9.00),
(2, 'A0_1', 8.50, 20.00, 10.00),
(3, 'A0_2', 10.00, 20.00, 12.00),
(4, 'A0_3', 12.50, 25.00, 15.00),
(5, 'A0_4', 15.00, 25.00, 25.00),
(6, 'A1', 3.00, 12.00, 7.00),
(7, 'A1_1', 4.50, 15.00, 8.00),
(8, 'A1_2', 5.50, 15.00, 8.00),
(9, 'A1_3', 6.50, 18.00, 0.00),
(10, 'A1_4', 7.00, 18.00, 0.00),
(11, 'A2', 1.50, 8.00, 4.00),
(12, 'A2_1', 2.00, 8.00, 5.00),
(13, 'A2_2', 2.50, 8.00, 5.00),
(14, 'A2_3', 3.00, 12.00, 0.00),
(15, 'A2_4', 4.00, 13.00, 0.00),
(16, 'A3', 1.00, 5.00, 1.00),
(17, 'A4', 0.50, 2.00, 0.50),
(18, '文本-彩色A3', 0.00, 0.00, 5.00),
(19, '文本-彩色A4', 0.00, 0.00, 2.50),
(20, '文本-简装', 0.00, 10.00, 20.00),
(21, '文本-软精装', 0.00, 0.00, 50.00);

-- --------------------------------------------------------

--
-- 表的结构 `green_drawplan_designer`
--

CREATE TABLE IF NOT EXISTS `green_drawplan_designer` (
  `id` int(20) NOT NULL COMMENT '自增ID',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `monomer_name` varchar(100) NOT NULL COMMENT '单体名称',
  `drawplan_project1` varchar(200) DEFAULT NULL COMMENT '工程参与人员',
  `drawplan_project2` varchar(200) DEFAULT NULL COMMENT '工程实参',
  `project_remarks` varchar(200) DEFAULT NULL COMMENT '工程备注',
  `drawplan_type1` varchar(200) DEFAULT NULL COMMENT '工种参',
  `drawplan_type2` varchar(200) DEFAULT NULL COMMENT '工种实参',
  `type_remarks` varchar(200) DEFAULT NULL COMMENT '工种备注',
  `drawplan_designer1` varchar(200) DEFAULT NULL COMMENT '设计参',
  `drawplan_designer2` varchar(200) DEFAULT NULL COMMENT '设计实参',
  `designer_remarks` varchar(200) DEFAULT NULL COMMENT '设计备注',
  `drawplan_drafting1` varchar(200) DEFAULT NULL COMMENT '制图参',
  `drawplan_drafting2` varchar(200) DEFAULT NULL COMMENT '制图实参',
  `drafting_remarks` varchar(200) DEFAULT NULL COMMENT '制图备注',
  `drawplan_check1` varchar(200) DEFAULT NULL COMMENT '校对参',
  `drawplan_check2` varchar(200) DEFAULT NULL COMMENT '校对实参',
  `check_remarks` varchar(200) DEFAULT NULL COMMENT '校对备注',
  `drawplan_verify1` varchar(200) DEFAULT NULL COMMENT '审核参',
  `drawplan_verify2` varchar(200) DEFAULT NULL COMMENT '审核实参',
  `verify_remarks` varchar(200) DEFAULT NULL COMMENT '审核备注',
  `drawplan_authorize1` varchar(200) DEFAULT NULL COMMENT '审定参',
  `drawplan_authorize2` varchar(200) DEFAULT NULL COMMENT '审定实参',
  `authorize_remarks` varchar(200) DEFAULT NULL COMMENT '审定备注'
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='参与人员表';

--
-- 转存表中的数据 `green_drawplan_designer`
--

INSERT INTO `green_drawplan_designer` (`id`, `project_id`, `monomer_name`, `drawplan_project1`, `drawplan_project2`, `project_remarks`, `drawplan_type1`, `drawplan_type2`, `type_remarks`, `drawplan_designer1`, `drawplan_designer2`, `designer_remarks`, `drawplan_drafting1`, `drawplan_drafting2`, `drafting_remarks`, `drawplan_check1`, `drawplan_check2`, `check_remarks`, `drawplan_verify1`, `drawplan_verify2`, `verify_remarks`, `drawplan_authorize1`, `drawplan_authorize2`, `authorize_remarks`) VALUES
(3, '19-16', '总图', '胡刚', '胡刚', NULL, '胡刚', '胡刚', NULL, '林娜娜、李凌培', '林娜娜', NULL, '林娜娜、李凌培', '林娜娜', NULL, '陈赛', '陈赛', NULL, '王宇飞', '王宇飞', NULL, '王宇飞', '王宇飞', NULL),
(28, '17-13', '1号楼', '胡刚', NULL, NULL, '胡刚', NULL, NULL, '薛德远', NULL, NULL, '薛德远', NULL, NULL, '陈赛', NULL, NULL, '王宇飞', NULL, NULL, '王宇飞', NULL, NULL),
(29, '17-13', '2、5、6~8号楼', '胡刚', NULL, NULL, '胡刚', NULL, NULL, '薛德远', NULL, NULL, '薛德远', NULL, NULL, '陈赛', NULL, NULL, '王宇飞', NULL, NULL, '王宇飞', NULL, NULL),
(30, '17-13', '3号楼', '胡刚', NULL, NULL, '胡刚', NULL, NULL, '薛德远', NULL, NULL, '薛德远', NULL, NULL, '陈赛', NULL, NULL, '王宇飞', NULL, NULL, '王宇飞', NULL, NULL),
(31, '17-13', '总初', '胡刚', NULL, NULL, '胡刚', NULL, NULL, '王丽芝', NULL, NULL, '王丽芝', NULL, NULL, '陈赛', NULL, NULL, '王宇飞', NULL, NULL, '王宇飞', NULL, NULL),
(32, '17-13', '4号楼', '胡刚', '胡刚', NULL, NULL, '胡刚', NULL, '陈赛', '陈赛', NULL, '陈赛', '陈赛', NULL, NULL, '王丽芝', NULL, NULL, '王宇飞', NULL, NULL, '王宇飞', NULL),
(4, '19-16', '地下室', '胡刚', '胡刚', NULL, '胡刚', '胡刚', NULL, '林娜娜、李凌培', '林娜娜', NULL, '林娜娜、李凌培', '林娜娜', NULL, '陈赛', '陈赛', NULL, '王宇飞', '王宇飞', NULL, '王宇飞', '王宇飞', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_economic_indicators`
--

CREATE TABLE IF NOT EXISTS `green_economic_indicators` (
  `id` int(20) NOT NULL COMMENT '自增ID',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `entry_name` varchar(100) DEFAULT NULL COMMENT '单体名称',
  `aboveground_area` varchar(30) DEFAULT NULL COMMENT '地上面积（含架空）',
  `underground_area` varchar(30) DEFAULT NULL COMMENT '地下面积（含人防）',
  `civil_air_defense` varchar(30) DEFAULT NULL COMMENT '其中人防',
  `layer_number` varchar(30) DEFAULT NULL COMMENT '层数',
  `height` varchar(30) DEFAULT NULL COMMENT '高度',
  `category` varchar(30) DEFAULT NULL COMMENT '类别',
  `notes` varchar(30) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='出图计划经济指标';

--
-- 转存表中的数据 `green_economic_indicators`
--

INSERT INTO `green_economic_indicators` (`id`, `project_id`, `entry_name`, `aboveground_area`, `underground_area`, `civil_air_defense`, `layer_number`, `height`, `category`, `notes`) VALUES
(2, '19-16', '地下室', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '19-16', '总图', '221264.04', '87074', '21450', NULL, NULL, NULL, NULL),
(25, '19-16', '地下室', '0', '87074', '21450', '2', '7.0', '甲类核六常六', NULL),
(28, '17-13', '1号楼', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '17-13', '2、5、6~8号楼', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '17-13', '3号楼', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, '17-13', '总初', '72956', '25694', NULL, NULL, NULL, NULL, NULL),
(32, '17-13', '4号楼', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, '00-01', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_equipment_fee`
--

CREATE TABLE IF NOT EXISTS `green_equipment_fee` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_classification` varchar(100) NOT NULL COMMENT '项目分类',
  `strong_electricity` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '强电专业',
  `water` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '水专业',
  `weak_current` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '弱电专业',
  `HVAC` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '暖通专业',
  `unitprice_remarks` varchar(200) NOT NULL COMMENT '专业备注'
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_equipment_fee`
--

INSERT INTO `green_equipment_fee` (`id`, `project_classification`, `strong_electricity`, `water`, `weak_current`, `HVAC`, `unitprice_remarks`) VALUES
(1, '别墅联排', 0.70, 0.70, 0.30, 12.00, '强电不少于500元/款、水不少于500元/款、弱电不少于300元/款'),
(2, '多层住宅', 0.50, 0.45, 0.30, 0.00, '强电不少于500元/款、水不少于500元/款、弱电不少于300元/款'),
(3, '高层住宅', 0.75, 0.65, 0.27, 0.12, ''),
(4, '商业', 0.90, 0.85, 0.50, 0.30, ''),
(12, '修改图', 0.00, 0.00, 0.00, 0.00, '特殊情况，手动输入'),
(5, '办公楼', 0.85, 0.80, 0.55, 0.30, ''),
(11, '无人防地下室', 0.35, 0.32, 0.10, 0.30, ''),
(6, '幼儿园', 0.55, 0.50, 0.50, 0.30, ''),
(7, '学校（除宿舍外）', 0.55, 0.50, 0.50, 0.30, ''),
(8, '文体医疗', 1.00, 0.95, 0.50, 0.30, ''),
(9, '工业', 0.35, 0.32, 0.10, 0.30, ''),
(10, '有人防地下室', 0.35, 0.32, 0.10, 0.30, ''),
(13, '零星工程', 0.00, 0.00, 0.00, 0.00, '特殊情况，手动输入');

-- --------------------------------------------------------

--
-- 表的结构 `green_ledgernode`
--

CREATE TABLE IF NOT EXISTS `green_ledgernode` (
  `id` int(10) NOT NULL COMMENT 'id',
  `contract_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '合同编号',
  `ledgernode_paymentratio` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '支付比例',
  `ledgernode_payment` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '付款金额',
  `ledgernode_require` varchar(300) CHARACTER SET utf8 DEFAULT NULL COMMENT '支付节点要求',
  `ledgernode_status` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '该节点是否付款'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `green_personalvalue`
--

CREATE TABLE IF NOT EXISTS `green_personalvalue` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(11) NOT NULL COMMENT '人员名字',
  `draw_date` date DEFAULT NULL COMMENT '出图时间',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL DEFAULT '' COMMENT '工程名称',
  `entry_name` varchar(300) NOT NULL DEFAULT '' COMMENT '项目名称',
  `output_value` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '产值（元）',
  `staff_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='个人产值表';

--
-- 转存表中的数据 `green_personalvalue`
--

INSERT INTO `green_personalvalue` (`id`, `staff_department`, `staff_name`, `draw_date`, `project_id`, `project_name`, `entry_name`, `output_value`, `staff_remarks`) VALUES
(4, '建筑二所', '陈赛', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' '),
(5, '院长室', '王宇飞', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' '),
(6, '院长室', '胡刚', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' ');

-- --------------------------------------------------------

--
-- 表的结构 `green_personalvalue林娜娜、李凌培`
--

CREATE TABLE IF NOT EXISTS `green_personalvalue林娜娜、李凌培` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(11) NOT NULL COMMENT '人员名字',
  `draw_date` date DEFAULT NULL COMMENT '出图时间',
  `project_id` varchar(30) DEFAULT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `output_value` float(10,2) DEFAULT NULL COMMENT '产值（元）',
  `staff_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='林娜娜、李凌培个人产值';

--
-- 转存表中的数据 `green_personalvalue林娜娜、李凌培`
--

INSERT INTO `green_personalvalue林娜娜、李凌培` (`id`, `staff_department`, `staff_name`, `draw_date`, `project_id`, `project_name`, `entry_name`, `output_value`, `staff_remarks`) VALUES
(1, '暂无部门', '林娜娜、李凌培', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', 0.00, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_personalvalue王宇飞`
--

CREATE TABLE IF NOT EXISTS `green_personalvalue王宇飞` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(11) NOT NULL COMMENT '人员名字',
  `draw_date` date DEFAULT NULL COMMENT '出图时间',
  `project_id` varchar(30) DEFAULT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `output_value` float(10,2) DEFAULT NULL COMMENT '产值（元）',
  `staff_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='王宇飞个人产值';

--
-- 转存表中的数据 `green_personalvalue王宇飞`
--

INSERT INTO `green_personalvalue王宇飞` (`id`, `staff_department`, `staff_name`, `draw_date`, `project_id`, `project_name`, `entry_name`, `output_value`, `staff_remarks`) VALUES
(1, '院长室', '王宇飞', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' ');

-- --------------------------------------------------------

--
-- 表的结构 `green_personalvalue胡刚`
--

CREATE TABLE IF NOT EXISTS `green_personalvalue胡刚` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(11) NOT NULL COMMENT '人员名字',
  `draw_date` date DEFAULT NULL COMMENT '出图时间',
  `project_id` varchar(30) DEFAULT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `output_value` float(10,2) DEFAULT NULL COMMENT '产值（元）',
  `staff_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='胡刚个人产值';

--
-- 转存表中的数据 `green_personalvalue胡刚`
--

INSERT INTO `green_personalvalue胡刚` (`id`, `staff_department`, `staff_name`, `draw_date`, `project_id`, `project_name`, `entry_name`, `output_value`, `staff_remarks`) VALUES
(1, '院长室', '胡刚', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' '),
(2, '院长室', '胡刚', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' ');

-- --------------------------------------------------------

--
-- 表的结构 `green_personalvalue陈赛`
--

CREATE TABLE IF NOT EXISTS `green_personalvalue陈赛` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_name` varchar(11) NOT NULL COMMENT '人员名字',
  `draw_date` date DEFAULT NULL COMMENT '出图时间',
  `project_id` varchar(30) DEFAULT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `output_value` float(10,2) DEFAULT NULL COMMENT '产值（元）',
  `staff_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='陈赛个人产值';

--
-- 转存表中的数据 `green_personalvalue陈赛`
--

INSERT INTO `green_personalvalue陈赛` (`id`, `staff_department`, `staff_name`, `draw_date`, `project_id`, `project_name`, `entry_name`, `output_value`, `staff_remarks`) VALUES
(1, '建筑二所', '陈赛', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' '),
(2, '建筑二所', '陈赛', '2019-11-14', '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', 0.00, ' ');

-- --------------------------------------------------------

--
-- 表的结构 `green_project`
--

CREATE TABLE IF NOT EXISTS `green_project` (
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_contractor` varchar(300) DEFAULT NULL COMMENT '发包人',
  `project_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `project_landarea` varchar(200) DEFAULT NULL COMMENT '用地面积',
  `aboveground_area` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '地上面积',
  `underground_area` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '地下面积',
  `among_area` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '其中人防',
  `project_totalarea` varchar(200) DEFAULT NULL COMMENT '总建筑面积',
  `project_leader` varchar(300) DEFAULT NULL COMMENT '项目负责人',
  `project_buildtype` varchar(50) DEFAULT NULL COMMENT '建筑类型',
  `project_design` varchar(140) DEFAULT NULL COMMENT '设计内容',
  `project_remark` varchar(200) DEFAULT NULL COMMENT '备注',
  `project_notice` varchar(200) DEFAULT NULL COMMENT '公告'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_project`
--

INSERT INTO `green_project` (`project_id`, `project_name`, `project_contractor`, `project_agent`, `project_landarea`, `aboveground_area`, `underground_area`, `among_area`, `project_totalarea`, `project_leader`, `project_buildtype`, `project_design`, `project_remark`, `project_notice`) VALUES
('17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '36478.00', 74058.33, 25885.90, 5156.60, '99944.23', ' 胡刚', ' 住宅', ' 建筑,结构,给排水,暖通,电气,管网', NULL, ''),
('19-01', '洞头三盘产权式酒店项目', ' 温州市洞头金海岸大酒店开发有限公司', ' 无', '76363', 23280.35, 18035.60, 0.00, '41315.95', ' 杨衍', '公建', ' 建筑,结构,给排水,暖通,电气,弱电,管网', '东区（无暖通）、西区；人防异地安置', ''),
('19-02', '温州市滨江商务区桃花岛片区T05-07地块 房地产建设工程', ' 温州德信江滨置业有限公司 ', ' 无', '29523', 96163.26, 35065.00, 10028.70, '131228.26', ' 胡刚', '住宅、商业', ' 建筑,结构,给排水,暖通,电气,管网', '地上含架空面积：1395.26平', ''),
('19-03', '温州市核心片区葡萄棚单元A-08、A-04地块房地产开发建设项目', ' 温州万旭置业有限公司', ' 无', '11988.9', 34702.72, 11430.76, 3390.62, '46133.48', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气,管网,内装,内装机电', '地上面积含架空1133.52平', ''),
('19-05', '温州市鹿城区七都片区北单元03-B-22号地块项目', ' 温州润睿房地产开发有限公司', ' 无', '35308.2', 84912.53, 34085.00, 8527.00, '118997.53', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,管网,内装,内装机电', '地上面积含架空172.61平', ''),
('19-06', '瑞安市瑞祥新区01-9、01-10地块房地产项目', ' 瑞安市万昆置业有限公司', ' 无', '70150.74', 197252.00, 59824.00, 15659.00, '257076', ' 杨衍', '住宅', ' 建筑,结构,给排水,暖通,电气,管网', '地上含架空面积：1532平', ''),
('19-07', '温州市核心片区会昌河单元B-07地块项目', ' 温州市弘途房地产开发有限公司', ' 无', '19967.2', 56870.30, 29107.00, 5655.00, '85977.3', ' 杨衍', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,管网,内装,PC', '地上面积含架空962.14平', ''),
('19-08', '温州市城市中心区横渎北A-25地块项目', ' 温州鼎润房地产开发有限公司', ' 无', '66974', 202092.00, 75530.00, 20097.97, '277622', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,管网,内装机电,BIM', '地上含架空面积：2477平', ''),
('19-09', '温州市核心片区站南片区林村旧村改造B地块项目', ' 温州首开中庚实业有限公司', ' 无', '130907', 470510.31, 191163.00, 42910.00, '661673.3', ' 杨衍', '公建、住宅（安置房）', ' 建筑,结构,给排水,暖通,电气,管网', '1号区块用地94854,2平，总：461341.6，地上322503.6平，架空4200，下：134638；2号区块用地：36053，总200331.7，上140606.7，架空3200，下56525', ''),
('19-10', '温州市生命健康小镇（茶白片区上蔡单元A片区）项目', ' 温州龙悦房地产开发有限公司', ' 无', '23997.4', 56485.40, 25978.60, 5627.55, '82464', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气,管网,内装,内装机电', NULL, ''),
('19-108', '杨府山北片03-02-16地块垃圾转运站及环卫工人倒班宿舍', ' 温州市城市建设投资集团有限公司', ' 无', '2181', 3253.00, 606.00, 0.00, '3859', ' 胡刚', '公建、住宅', ' 总包,建筑,结构,给排水,暖通,电气,弱电,管网,景观,基坑,燃气', NULL, ''),
('19-109', '温州市洪殿单元F-22地块社会福利院工程', ' 温州市洪殿单元F-22地块社会福利院工程', ' 无', '3700', 5550.00, 2693.00, 787.00, '8243', ' 胡刚', '公建', ' 建筑,结构,给排水,暖通,电气,弱电,管网,景观,基坑,燃气', NULL, ''),
('19-11', '温州市茶白片区梧田南片区C-11地块', ' 温州市凯壹置业有限公司', ' 无', '24251.7', 68667.49, 31754.00, 6799.83, '100421.5', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气', '地上面积含架空：764.69平', ''),
('19-12', '龙港镇象北区块控规D01-04地块商住房项目', ' 温州汇龙置业有限公司', ' 无', '41456', 99143.96, 31391.02, 6779.20, '130534.98', ' 杨衍', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,景观,幕墙', '地上建筑面积里含架空3795.16平', ''),
('19-13', '温州市滨江商务区桃花岛片区T05-14a地块', ' 温州和盟置业有限公司', ' 无', '39608', 136467.00, 46500.00, 13200.00, '182967', ' 杨衍', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,管网', '地上建筑面积含架空1800平', ''),
('19-15', '温州市三溪片区货站单元B-07地块', ' 温州万晋置业有限公司', ' 无', '69099', 208558.86, 82985.90, 20169.80, '291544.76', ' 胡刚', '住宅、商业', ' 建筑,结构,给排水,暖通,电气,管网,内装', '地上建筑面积 含架空1261.86平', ''),
('19-16', '温州市瓯海中心单元北片28-C-01地块工程', ' 温州市悦达置业有 限公司', ' 无', '74399.3', 308338.03, 87074.00, 21450.00, '395412.04', ' 胡刚', '住宅、商业', ' 建筑,结构,给排水,暖通,电气,管网,内装', '地上建筑含架空5506.07平', ''),
('19-17', '瑞安市莘塍街道南垟村安置留底二号地块项目', ' 瑞安市梁越置业有限公司', ' 无', '22106.14', 53053.73, 15850.00, 3999.00, '68903.73', ' 杨衍', '住宅', '结构,给排水,暖通,电气,弱电,景观, 建筑,', NULL, ''),
('19-20', '温州市老港区03-01-19地块', ' 未知', ' 无', '25041', 92967.94, 43089.37, 8742.85, '136057.31', ' 胡刚', '住宅', ' 建筑,结构,给排水,暖通,电气,弱电,管网,景观,内装', '地上建筑面积含架空1658.47平', '');

-- --------------------------------------------------------

--
-- 表的结构 `green_projectconstruction`
--

CREATE TABLE IF NOT EXISTS `green_projectconstruction` (
  `project_id` varchar(20) NOT NULL COMMENT '工程号',
  `project_design` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '设计内容',
  `project_time` date NOT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='已完成阶段施工图';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectdraw`
--

CREATE TABLE IF NOT EXISTS `green_projectdraw` (
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `draw_concept` varchar(20) DEFAULT NULL COMMENT '概念方案',
  `draw_plan` varchar(20) DEFAULT NULL COMMENT '方案',
  `draw_preliminary` varchar(20) DEFAULT NULL COMMENT '初步',
  `draw_pile` varchar(20) DEFAULT NULL COMMENT '桩基图',
  `draw_work` varchar(20) DEFAULT NULL COMMENT '施工图',
  `draw_modify` varchar(20) DEFAULT NULL COMMENT '修改图',
  `contact` varchar(20) DEFAULT NULL COMMENT '联系单',
  `others` varchar(20) DEFAULT NULL COMMENT '其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_projectdraw`
--

INSERT INTO `green_projectdraw` (`project_id`, `draw_concept`, `draw_plan`, `draw_preliminary`, `draw_pile`, `draw_work`, `draw_modify`, `contact`, `others`) VALUES
('17-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-01', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-02', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-03', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-05', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-06', NULL, '已完成', NULL, NULL, '	 已完成', NULL, NULL, NULL),
('19-07', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-08', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-09', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-10', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-108', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-109', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-11', NULL, '已完成', NULL, NULL, '已完成', NULL, NULL, NULL),
('19-12', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-13', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-15', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-16', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-17', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL),
('19-20', NULL, '已完成', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_projectdrawplan`
--

CREATE TABLE IF NOT EXISTS `green_projectdrawplan` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `project_contractor` varchar(300) DEFAULT NULL COMMENT '发包人',
  `project_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `monomer_name` varchar(100) NOT NULL COMMENT '单体名称',
  `drawplan_major` varchar(30) NOT NULL COMMENT '专业',
  `drawplan_phase` varchar(30) DEFAULT NULL COMMENT '阶段',
  `figure_number` varchar(11) NOT NULL COMMENT '图号',
  `drawplan_number` varchar(50) DEFAULT NULL COMMENT '图数',
  `price` float NOT NULL DEFAULT '0' COMMENT '出图价格',
  `drawplan_sepcific` varchar(1500) DEFAULT NULL COMMENT '出图规格',
  `drawplan_member` varchar(300) DEFAULT NULL COMMENT '参与人员',
  `drawplan_survey` varchar(100) DEFAULT NULL COMMENT '工程概况',
  `drawplan_date` varchar(20) NOT NULL COMMENT '日期',
  `drawplan_remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='出图登记表';

--
-- 转存表中的数据 `green_projectdrawplan`
--

INSERT INTO `green_projectdrawplan` (`id`, `project_id`, `project_name`, `project_contractor`, `project_agent`, `monomer_name`, `drawplan_major`, `drawplan_phase`, `figure_number`, `drawplan_number`, `price`, `drawplan_sepcific`, `drawplan_member`, `drawplan_survey`, `drawplan_date`, `drawplan_remarks`) VALUES
(5, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', ' 温州市悦达置业有 限公司', ' 无', '总图', '建筑', '施工图', '总施-01~总施-02', '19', 0, NULL, '胡刚;胡刚;林娜娜林娜娜;陈赛;陈赛;陈赛;', '总建筑面积308338.04平方米', '2019-11-14', ' '),
(6, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', ' 温州市悦达置业有 限公司', ' 无', '地下室', '建筑', '施工图', '建施-01~建施-45', '19', 0, NULL, '胡刚;胡刚;林娜娜、李凌培林娜娜、李凌培;陈赛;王宇飞;王宇飞;', '总建筑面积308338.04平方米', '2019-11-14', NULL),
(7, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '1号楼', '建筑', '初步', '建初01~09', '19', 0, NULL, '', '', '2017-09-12', '0'),
(8, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '2、5、6~8号楼', '建筑', '初步', '建初01~09', '19', 0, NULL, '', NULL, '2017-09-12', NULL),
(9, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '3号楼', '建筑', '初步', '建初01~09', '19', 0, NULL, '', NULL, '2017-09-12', NULL),
(10, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '总初', '建筑', '初步', '建初01~09', '5', 0, NULL, '', NULL, '2017-09-12', NULL),
(11, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', ' 平阳德行置业有限公司', ' 平阳德行置业有限公司', '4号楼', '建筑', '初步', '建初01~11', '11', 0, NULL, '胡刚;胡刚;陈赛陈赛;王丽芝;王宇飞;王宇飞;', NULL, '2017-09-12', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_projectphase`
--

CREATE TABLE IF NOT EXISTS `green_projectphase` (
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `phase_name1` varchar(20) DEFAULT NULL COMMENT '合同已签',
  `phase_name2` varchar(20) DEFAULT NULL COMMENT '方案',
  `phase_name3` varchar(20) DEFAULT NULL COMMENT '方案批复',
  `phase_name4` varchar(20) DEFAULT NULL COMMENT '初步',
  `phase_name5` varchar(20) DEFAULT NULL COMMENT '初步批复',
  `phase_name6` varchar(20) DEFAULT NULL COMMENT '桩基图',
  `phase_name7` varchar(20) DEFAULT NULL COMMENT '桩基批复',
  `phase_name8` varchar(20) DEFAULT NULL COMMENT '施工图',
  `phase_name9` varchar(20) DEFAULT NULL COMMENT '施工图批复',
  `phase_name10` varchar(20) DEFAULT NULL COMMENT '审批',
  `phase_name11` varchar(20) DEFAULT NULL COMMENT '开工',
  `phase_name12` varchar(20) DEFAULT NULL COMMENT '交底',
  `phase_name13` varchar(20) DEFAULT NULL COMMENT '管网',
  `phase_name14` varchar(20) DEFAULT NULL COMMENT '基础完工',
  `phase_name15` varchar(20) DEFAULT NULL COMMENT '地下完工',
  `phase_name16` varchar(20) DEFAULT NULL COMMENT '结顶',
  `phase_name17` varchar(20) DEFAULT NULL COMMENT '室外工程',
  `phase_name18` varchar(20) DEFAULT NULL COMMENT '竣工验收',
  `phase_name19` varchar(20) DEFAULT NULL COMMENT '竣工备案',
  `phase_name20` varchar(20) DEFAULT NULL COMMENT '已结算'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_projectphase`
--

INSERT INTO `green_projectphase` (`project_id`, `phase_name1`, `phase_name2`, `phase_name3`, `phase_name4`, `phase_name5`, `phase_name6`, `phase_name7`, `phase_name8`, `phase_name9`, `phase_name10`, `phase_name11`, `phase_name12`, `phase_name13`, `phase_name14`, `phase_name15`, `phase_name16`, `phase_name17`, `phase_name18`, `phase_name19`, `phase_name20`) VALUES
('19-07', '2019-07-14', '2019-07-22', '2019-07-22', NULL, NULL, NULL, NULL, '2019-08-14', '2019-08-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-08', '2019-09-23', '2019-07-19', '2019-09-04', NULL, NULL, NULL, NULL, '2019-09-10', '2019-09-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-06', '2019-06-13', '2019-05-22', '2019-06-04', NULL, NULL, NULL, NULL, '2019-07-15', '2019-08-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-05', '2019-08-13', '2019-08-02', '2019-08-13', NULL, NULL, NULL, NULL, '2019-08-18', '2019-08-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-03', '2019-06-13', '2019-07-01', '2019-08-01', NULL, NULL, NULL, NULL, '2019-08-10', '2019-08-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-02', '2019-04-22', '2019-04-17', '2019-05-10', NULL, NULL, NULL, NULL, '2019-06-10', '2019-06-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-01', '2019-02-19', '2019-03-25', '2019-08-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-17', NULL, '2019-09-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-09', '2019-09-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-10', '2019-08-16', '2019-08-05', '2019-09-16', NULL, NULL, NULL, NULL, '2019-09-15', '2019-10-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-11', '2019-09-24', '2019-08-13', '2019-09-05', NULL, NULL, NULL, NULL, '2019-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-12', '2019-08-20', '2019-09-11', '2019-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-13', NULL, '2019-09-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-15', NULL, '2019-10-14', '2019-11-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-16', NULL, '2019-09-17', '2019-10-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-20', NULL, '2019-12-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-108', NULL, '2019-09-27', NULL, '2019-10-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19-109', NULL, '2019-09-29', '2019-11-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('17-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue` (
  `ID` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(100) NOT NULL COMMENT '项目名称',
  `design_area` varchar(30) NOT NULL COMMENT '设计面积',
  `contract_amount` varchar(11) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(30) NOT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) NOT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(30) NOT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(30) NOT NULL COMMENT '其他系数',
  `major` varchar(10) NOT NULL COMMENT '专业',
  `designer` varchar(200) NOT NULL COMMENT '设计人员',
  `designe_price` float(10,2) NOT NULL COMMENT '设计单价',
  `designe_value` float(10,2) NOT NULL COMMENT '设计产值',
  `proofreader` varchar(200) NOT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) NOT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) NOT NULL COMMENT '校对产值',
  `auditor` varchar(200) NOT NULL COMMENT '审核人员',
  `audit_price` float(10,2) NOT NULL COMMENT '审核单价',
  `audit_value` float(10,2) NOT NULL COMMENT '审核产值',
  `work_boss` varchar(200) NOT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) NOT NULL COMMENT '工种单价',
  `work_value` float(10,2) NOT NULL COMMENT '工种产值',
  `project_boss` varchar(200) NOT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) NOT NULL COMMENT '工程单价',
  `project_value` float(10,2) NOT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) NOT NULL COMMENT '其他费用',
  `value_subtotal` float(10,2) NOT NULL COMMENT '小计',
  `department` varchar(30) NOT NULL COMMENT '部门',
  `drawing_time` date NOT NULL COMMENT '出图时间',
  `remarks` varchar(200) NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目产值表';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue17-13`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue17-13` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='17-13工程产值';

--
-- 转存表中的数据 `green_projectvalue17-13`
--

INSERT INTO `green_projectvalue17-13` (`id`, `project_id`, `project_name`, `entry_name`, `project_subcontractor`, `design_area`, `contract_amount`, `stage_proportions`, `difficulty_system`, `distribution_ratio`, `residual_coefficient`, `drawplan_major`, `designer`, `design_price`, `design_value`, `proofreader`, `proofreading_price`, `proofreading_value`, `auditor`, `audit_price`, `audit_value`, `work_boss`, `work_basenumber`, `work_value`, `project_boss`, `project_basenumber`, `project_value`, `other_expenses`, `value_subtotal`, `department`, `drawing_time`, `remarks`) VALUES
(1, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', '1号楼', ' 平阳**置业有限公司', NULL, NULL, NULL, NULL, NULL, NULL, '建筑', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2017-09-12', NULL),
(2, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', '2、5、6~8号楼', ' 平阳**置业有限公司', NULL, NULL, NULL, NULL, NULL, NULL, '建筑', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2017-09-12', NULL),
(3, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', '3号楼', ' 平阳**置业有限公司', NULL, NULL, NULL, NULL, NULL, NULL, '建筑', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2017-09-12', NULL),
(4, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', '总初', ' 平阳**置业有限公司', NULL, NULL, NULL, NULL, NULL, NULL, '建筑', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2017-09-12', NULL),
(5, '17-13', '平阳县鳌江镇鸽巢路C-06-01地块', '4号楼', ' 平阳**置业有限公司', '123456', '100000', '0.3', '0.1', '0.2', '1', '建筑', '陈赛', 20.00, 12000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2017-09-12', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-01`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-01` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(200) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(200) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(200) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(200) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-01工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-02`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-02` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(200) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(200) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(200) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(200) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-02工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-03`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-03` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(200) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(200) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(200) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(200) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-03工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-05`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-05` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-05工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-06`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-06` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-06工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-07`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-07` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-07工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-08`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-08` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-08工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-09`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-09` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-09工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-10`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-10` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-10工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-11`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-11` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-11工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-12`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-12` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-12工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-13`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-13` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-13工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-15`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-15` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-15工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-16`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-16` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='19-16工程产值';

--
-- 转存表中的数据 `green_projectvalue19-16`
--

INSERT INTO `green_projectvalue19-16` (`id`, `project_id`, `project_name`, `entry_name`, `project_subcontractor`, `design_area`, `contract_amount`, `stage_proportions`, `difficulty_system`, `distribution_ratio`, `residual_coefficient`, `drawplan_major`, `designer`, `design_price`, `design_value`, `proofreader`, `proofreading_price`, `proofreading_value`, `auditor`, `audit_price`, `audit_value`, `work_boss`, `work_basenumber`, `work_value`, `project_boss`, `project_basenumber`, `project_value`, `other_expenses`, `value_subtotal`, `department`, `drawing_time`, `remarks`) VALUES
(1, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-11-14', NULL),
(24, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '总图', ' 温州市悦达置业有限公司', '0', '0', '0', '0', '0', '0', '建筑', '陈赛', 0.00, 0.00, '陈赛', 0.00, 0.00, '王宇飞', 0.00, 0.00, '胡刚', 0.00, 0.00, '胡刚', 0.00, 0.00, 0.00, 0.00, '建筑', '2019-11-14', ' '),
(25, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', NULL, '87074', '0', '0', '0', '0', '0', '建筑', '林娜娜、李凌培', 0.00, 0.00, '陈赛', 0.00, 0.00, '王宇飞', 0.00, 0.00, '胡刚', 0.00, 0.00, '胡刚', 0.00, 0.00, 0.00, 0.00, '建筑', NULL, NULL),
(26, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', NULL, '87074', '0', '0', '0', '0', '0', '建筑', '林娜娜、李凌培', 0.00, 0.00, '陈赛', 0.00, 0.00, '王宇飞', 0.00, 0.00, '胡刚', 0.00, 0.00, '胡刚', 0.00, 0.00, 0.00, 0.00, '建筑', NULL, NULL),
(27, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', NULL, '87074', '0', '0', '0', '0', '0', '建筑', '林娜娜、李凌培', 0.00, 0.00, '陈赛', 0.00, 0.00, '王宇飞', 0.00, 0.00, '胡刚', 0.00, 0.00, '胡刚', 0.00, 0.00, 0.00, 0.00, '建筑', '2019-11-14', NULL),
(28, '19-16', '温州市瓯海中心单元北片28-C-01地块工程', '地下室', ' 温州市悦达置业有 限公司', '87074', '0', '0', '0', '0', '0', '建筑', '林娜娜、李凌培', 0.00, 0.00, '陈赛', 0.00, 0.00, '王宇飞', 0.00, 0.00, '胡刚', 0.00, 0.00, '胡刚', 0.00, 0.00, 0.00, 0.00, '建筑', '2019-11-14', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-17`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-17` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-17工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-20`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-20` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-20工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-108`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-108` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-108工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvalue19-109`
--

CREATE TABLE IF NOT EXISTS `green_projectvalue19-109` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '单体名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) DEFAULT NULL COMMENT '分包人',
  `design_area` varchar(50) DEFAULT NULL COMMENT '设计面积',
  `contract_amount` varchar(50) DEFAULT NULL COMMENT '主体合同额',
  `stage_proportions` varchar(50) DEFAULT NULL COMMENT '阶段比例',
  `difficulty_system` varchar(11) DEFAULT NULL COMMENT '难度系统',
  `distribution_ratio` varchar(11) DEFAULT NULL COMMENT '分配比例',
  `residual_coefficient` varchar(11) DEFAULT NULL COMMENT '其他系数',
  `drawplan_major` varchar(11) DEFAULT NULL COMMENT '专业',
  `designer` varchar(300) DEFAULT NULL COMMENT '设计人员',
  `design_price` float(10,2) DEFAULT NULL COMMENT '设计单价',
  `design_value` float(10,2) DEFAULT NULL COMMENT '设计产值',
  `proofreader` varchar(300) DEFAULT NULL COMMENT '校对人员',
  `proofreading_price` float(10,2) DEFAULT NULL COMMENT '校对单价',
  `proofreading_value` float(10,2) DEFAULT NULL COMMENT '校对产值',
  `auditor` varchar(300) DEFAULT NULL COMMENT '审核人员',
  `audit_price` float(10,2) DEFAULT NULL COMMENT '审核单价',
  `audit_value` float(10,2) DEFAULT NULL COMMENT '审核产值',
  `work_boss` varchar(300) DEFAULT NULL COMMENT '工种负责人',
  `work_basenumber` float(10,2) DEFAULT NULL COMMENT '工种单价',
  `work_value` float(10,2) DEFAULT NULL COMMENT '工种产值',
  `project_boss` varchar(300) DEFAULT NULL COMMENT '工程人员',
  `project_basenumber` float(10,2) DEFAULT NULL COMMENT '工程单价',
  `project_value` float(10,2) DEFAULT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) DEFAULT '0.00' COMMENT '其他费用',
  `value_subtotal` float(10,2) DEFAULT NULL COMMENT '小计',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  `drawing_time` date DEFAULT NULL COMMENT '出图时间',
  `remarks` varchar(140) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-109工程产值';

-- --------------------------------------------------------

--
-- 表的结构 `green_projectvaluemajor`
--

CREATE TABLE IF NOT EXISTS `green_projectvaluemajor` (
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `major_number` varchar(11) NOT NULL COMMENT '专业编号',
  `major_name` varchar(10) NOT NULL COMMENT '专业名称',
  `programme` varchar(50) NOT NULL COMMENT '方案',
  `building` varchar(10) NOT NULL COMMENT '建筑',
  `structure` varchar(10) NOT NULL COMMENT '结构',
  `water_supply` varchar(10) NOT NULL COMMENT '给排水',
  `hvac` varchar(10) NOT NULL COMMENT '暖通',
  `electrical` varchar(10) NOT NULL COMMENT '电气',
  `weak_current` varchar(10) NOT NULL COMMENT '弱电',
  `pipe_network` varchar(10) NOT NULL COMMENT '管网'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目产值专业表';

-- --------------------------------------------------------

--
-- 表的结构 `green_project_totalvalue`
--

CREATE TABLE IF NOT EXISTS `green_project_totalvalue` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '项目名称',
  `project_subcontractor` varchar(300) NOT NULL COMMENT '发包人',
  `ground_floor_area` float(10,2) DEFAULT NULL COMMENT '地上建筑面积',
  `underground_building_area` float(10,2) DEFAULT NULL COMMENT '地下建筑面积',
  `total_building_area` float(10,2) DEFAULT NULL COMMENT '总建筑面积',
  `design_area` float(10,2) DEFAULT NULL COMMENT '设计面积',
  `subject_contract_amount` float(10,2) DEFAULT NULL COMMENT '主体合同额',
  `design_value` float(10,2) NOT NULL COMMENT '设计产值',
  `check_value` float(10,2) NOT NULL COMMENT '校对产值',
  `examine_value` float(10,2) NOT NULL COMMENT '审核产值',
  `worktype_value` float(10,2) NOT NULL COMMENT '工种产值',
  `project_value` float(10,2) NOT NULL COMMENT '工程产值',
  `other_expenses` float(10,2) NOT NULL COMMENT '其他费用',
  `total_department` float(10,2) NOT NULL COMMENT '部门合计'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `green_staff`
--

CREATE TABLE IF NOT EXISTS `green_staff` (
  `staff_id` int(11) NOT NULL COMMENT 'ID',
  `enable` int(11) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `staff_enable` int(20) NOT NULL DEFAULT '1' COMMENT '是否启用为员工',
  `administrators_name` varchar(20) NOT NULL COMMENT '登录名',
  `staff_name` varchar(20) NOT NULL COMMENT '姓名',
  `staff_phone` varchar(20) NOT NULL COMMENT '电话',
  `staff_telphole_1` varchar(20) NOT NULL DEFAULT '' COMMENT '手机1',
  `staff_extension` varchar(20) NOT NULL COMMENT '分机',
  `staff_position` varchar(20) NOT NULL COMMENT '职务',
  `staff_department` varchar(20) NOT NULL COMMENT '部门',
  `staff_status` varchar(20) NOT NULL COMMENT '状态（在职）',
  `staff_shortmobile` varchar(20) NOT NULL COMMENT '短号',
  `staff_practising` varchar(20) NOT NULL COMMENT '执业资格',
  `staff_title` varchar(20) NOT NULL DEFAULT '' COMMENT '职称',
  `staff_QQ` varchar(20) NOT NULL COMMENT 'QQ',
  `staff_wechat` varchar(20) NOT NULL COMMENT '微信',
  `staff_dingding` varchar(20) NOT NULL DEFAULT '' COMMENT '钉钉',
  `staff_email` varchar(20) NOT NULL DEFAULT '' COMMENT '邮箱',
  `staff_other` varchar(50) NOT NULL DEFAULT '' COMMENT '其他'
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_staff`
--

INSERT INTO `green_staff` (`staff_id`, `enable`, `staff_enable`, `administrators_name`, `staff_name`, `staff_phone`, `staff_telphole_1`, `staff_extension`, `staff_position`, `staff_department`, `staff_status`, `staff_shortmobile`, `staff_practising`, `staff_title`, `staff_QQ`, `staff_wechat`, `staff_dingding`, `staff_email`, `staff_other`) VALUES
(23, 0, 1, '13957729999', '王宇飞', '85509999', '13957729999', '8898', '院长', '院长室', '在职', '', '一级注册建筑师', '高级工程师', '', '', '', '', ''),
(26, 0, 1, '13857729999', '胡刚', '85509999', '13857729999', '8879', '副院长', '院长室', '在职', '', '一级注册建筑师', '高级工程师', '', '', '', '', ''),
(43, 0, 1, '13566189999', '林娜娜', '85509999', '13566189999', '8881', '建筑设计', '建筑二所', '在职', '', '其他', '高级工程师', '', '', '', '', ''),
(44, 0, 1, '13967719999', '陈赛', '85509999', '13967719999', '8890', '建筑设计', '建筑二所', '在职', '', '其他', '工程师', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `green_staffdepartment`
--

CREATE TABLE IF NOT EXISTS `green_staffdepartment` (
  `department_id` int(11) NOT NULL COMMENT '部门编号',
  `staff_depratment` varchar(10) NOT NULL COMMENT '部门名称'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_staffdepartment`
--

INSERT INTO `green_staffdepartment` (`department_id`, `staff_depratment`) VALUES
(1, '方案所'),
(2, '建筑一所'),
(3, '建筑二所'),
(4, '结构所'),
(5, '设备所'),
(6, '景观所'),
(7, '工业化所'),
(8, '内装');

-- --------------------------------------------------------

--
-- 表的结构 `green_structure_fee`
--

CREATE TABLE IF NOT EXISTS `green_structure_fee` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `project_classification` varchar(100) NOT NULL COMMENT '项目分类',
  `design_unit_price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '设计单价(元/平方米)',
  `unitprice_remarks` varchar(100) NOT NULL COMMENT '单价备注'
) ENGINE=MyISAM AUTO_INCREMENT=349 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `green_structure_fee`
--

INSERT INTO `green_structure_fee` (`id`, `project_classification`, `design_unit_price`, `unitprice_remarks`) VALUES
(1, '别墅联排', 6.00, '不少于4500元/款'),
(2, '多层住宅', 2.00, '不少于5500元/款'),
(3, '高层住宅', 1.75, '不少于6500元/款'),
(4, '商业', 3.30, ''),
(6, '幼儿园', 3.50, '每所不少于7000元'),
(7, '学校（除宿舍外）', 2.80, '特殊单体另行商议'),
(8, '文体医疗', 4.00, ''),
(9, '工业', 1.00, ''),
(10, '有人防地下室', 2.50, ''),
(11, '无人防地下室', 3.20, ''),
(12, '修改图', 0.00, '特殊情况，手动输入'),
(13, '零星工程', 0.00, '特殊情况，手动输入');

-- --------------------------------------------------------

--
-- 表的结构 `green_tosum`
--

CREATE TABLE IF NOT EXISTS `green_tosum` (
  `id` int(11) NOT NULL COMMENT '序号',
  `stampflag` varchar(20) NOT NULL COMMENT '明细时间戳',
  `project_id` varchar(30) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `project_contractor` varchar(300) NOT NULL COMMENT '发包人',
  `project_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `project_sort` varchar(20) DEFAULT NULL COMMENT '出图类型',
  `sum_fee` varchar(11) DEFAULT NULL COMMENT '加晒费总额',
  `sum_settled` varchar(11) DEFAULT NULL COMMENT '已结算加晒费',
  `sum_free` varchar(11) DEFAULT NULL COMMENT '免费金额',
  `sum_receivable` varchar(11) DEFAULT NULL COMMENT '应收金额',
  `sum_number` varchar(11) DEFAULT NULL COMMENT '图纸份数',
  `sum_date` date DEFAULT NULL COMMENT '加晒时间',
  `sum_remarks` varchar(100) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加晒费表格';

-- --------------------------------------------------------

--
-- 表的结构 `green_tosum17-13`
--

CREATE TABLE IF NOT EXISTS `green_tosum17-13` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `stampflag` varchar(20) NOT NULL COMMENT '明细时间戳',
  `project_id` varchar(50) NOT NULL COMMENT '工程号',
  `project_name` varchar(200) NOT NULL COMMENT '工程名称',
  `entry_name` varchar(200) NOT NULL COMMENT '单体名称',
  `project_contractor` varchar(300) DEFAULT NULL COMMENT '发包人',
  `project_agent` varchar(300) DEFAULT NULL COMMENT '代建方',
  `sum_fee` varchar(50) DEFAULT NULL COMMENT '加晒费总额',
  `sum_settled` varchar(50) DEFAULT NULL COMMENT '已结算加晒费',
  `sum_free` varchar(11) DEFAULT NULL COMMENT '免费金额',
  `project_sort` varchar(20) DEFAULT NULL COMMENT '加晒图分类',
  `sum_receivable` varchar(11) DEFAULT NULL COMMENT '应收金额',
  `sum_copies` int(11) DEFAULT NULL COMMENT '图纸数量',
  `sum_norms` varchar(140) DEFAULT NULL COMMENT '图纸规格',
  `sum_number` varchar(11) DEFAULT NULL COMMENT '图纸份数',
  `sum_untiprice` float(10,2) DEFAULT NULL COMMENT '单份合计',
  `sum_totalprice` float(10,2) DEFAULT NULL COMMENT '小计',
  `sum_date` date DEFAULT NULL COMMENT '加晒时间',
  `sum_remarks` varchar(200) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='17-13加晒记录';

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner17-13`
--

CREATE TABLE IF NOT EXISTS `projectdesigner17-13` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='17-13设计人员';

--
-- 转存表中的数据 `projectdesigner17-13`
--

INSERT INTO `projectdesigner17-13` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('17-13', 'BIM', NULL, NULL, NULL),
('17-13', 'PC', NULL, NULL, NULL),
('17-13', '中央空调', NULL, NULL, NULL),
('17-13', '亮丽', NULL, NULL, NULL),
('17-13', '其他', NULL, NULL, NULL),
('17-13', '内装', NULL, NULL, NULL),
('17-13', '内装机电', NULL, NULL, NULL),
('17-13', '基坑', NULL, NULL, NULL),
('17-13', '幕墙', NULL, NULL, NULL),
('17-13', '建筑', '胡刚', '王丽芝、薛德远、陈赛', ''),
('17-13', '弱电', NULL, NULL, NULL),
('17-13', '总包', NULL, NULL, NULL),
('17-13', '护坡', NULL, NULL, NULL),
('17-13', '景观', NULL, NULL, NULL),
('17-13', '暖通', '高杰', '高杰', ''),
('17-13', '燃气', NULL, NULL, NULL),
('17-13', '电力', NULL, NULL, NULL),
('17-13', '电气', '袁宏文', NULL, NULL),
('17-13', '管网', NULL, NULL, NULL),
('17-13', '结构', '陈凡', NULL, NULL),
('17-13', '给排水', '张亮', NULL, NULL),
('17-13', '钢构', NULL, NULL, NULL),
('17-13', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-01`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-01` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-01设计人员';

--
-- 转存表中的数据 `projectdesigner19-01`
--

INSERT INTO `projectdesigner19-01` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-01', 'BIM', NULL, NULL, NULL),
('19-01', 'PC', NULL, NULL, NULL),
('19-01', '中央空调', NULL, NULL, NULL),
('19-01', '亮丽', NULL, NULL, NULL),
('19-01', '其他', NULL, NULL, NULL),
('19-01', '内装', NULL, NULL, NULL),
('19-01', '内装机电', NULL, NULL, NULL),
('19-01', '基坑', NULL, NULL, NULL),
('19-01', '幕墙', NULL, NULL, NULL),
('19-01', '建筑', '杨衍', '姚笃格、涂星琼、王苒苒', NULL),
('19-01', '弱电', '袁宏文', '沈进军', NULL),
('19-01', '总包', NULL, NULL, NULL),
('19-01', '护坡', NULL, NULL, NULL),
('19-01', '景观', NULL, NULL, NULL),
('19-01', '暖通', '高杰', '高杰', NULL),
('19-01', '燃气', NULL, NULL, NULL),
('19-01', '电力', NULL, NULL, NULL),
('19-01', '电气', '袁宏文', '鲍海萍', NULL),
('19-01', '管网', NULL, NULL, NULL),
('19-01', '结构', '陈凡', '夏星星、王昊、朱建武、', NULL),
('19-01', '给排水', '姜尔特', '周小伟、金天豪、', NULL),
('19-01', '钢构', NULL, NULL, NULL),
('19-01', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-02`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-02` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-02设计人员';

--
-- 转存表中的数据 `projectdesigner19-02`
--

INSERT INTO `projectdesigner19-02` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-02', 'BIM', NULL, NULL, NULL),
('19-02', 'PC', NULL, NULL, NULL),
('19-02', '中央空调', NULL, NULL, NULL),
('19-02', '亮丽', NULL, NULL, NULL),
('19-02', '其他', NULL, NULL, NULL),
('19-02', '内装', NULL, NULL, NULL),
('19-02', '内装机电', NULL, NULL, NULL),
('19-02', '基坑', NULL, NULL, NULL),
('19-02', '幕墙', NULL, NULL, NULL),
('19-02', '建筑', '杨衍', '李海华、沈琼宇、陈阳', NULL),
('19-02', '弱电', NULL, NULL, NULL),
('19-02', '总包', NULL, NULL, NULL),
('19-02', '护坡', NULL, NULL, NULL),
('19-02', '景观', NULL, NULL, NULL),
('19-02', '暖通', '高杰', '高杰', NULL),
('19-02', '燃气', NULL, NULL, NULL),
('19-02', '电力', NULL, NULL, NULL),
('19-02', '电气', '袁宏文', '徐昌道、鲍海萍', NULL),
('19-02', '管网', NULL, NULL, NULL),
('19-02', '结构', '陈凡', '张晨、汪宇航、陈方阳、朱建武', NULL),
('19-02', '给排水', '周小伟', '周小伟、曾翰进、刘涛', NULL),
('19-02', '钢构', NULL, NULL, NULL),
('19-02', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-03`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-03` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-03设计人员';

--
-- 转存表中的数据 `projectdesigner19-03`
--

INSERT INTO `projectdesigner19-03` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-03', 'BIM', NULL, NULL, NULL),
('19-03', 'PC', NULL, NULL, NULL),
('19-03', '中央空调', NULL, NULL, NULL),
('19-03', '亮丽', NULL, NULL, NULL),
('19-03', '其他', NULL, NULL, NULL),
('19-03', '内装', NULL, NULL, NULL),
('19-03', '内装机电', NULL, NULL, NULL),
('19-03', '基坑', NULL, NULL, NULL),
('19-03', '幕墙', NULL, NULL, NULL),
('19-03', '建筑', '胡刚', '陈赛、杨洲、杨俏、张鸣一', NULL),
('19-03', '弱电', '无', NULL, NULL),
('19-03', '总包', NULL, '无', NULL),
('19-03', '护坡', NULL, NULL, NULL),
('19-03', '景观', NULL, NULL, NULL),
('19-03', '暖通', '高杰', '高杰', NULL),
('19-03', '燃气', NULL, NULL, NULL),
('19-03', '电力', NULL, NULL, NULL),
('19-03', '电气', '袁宏文', '鲍海萍', NULL),
('19-03', '管网', NULL, NULL, NULL),
('19-03', '结构', '郑敏', '吴婷婷、郑敏、钱波、叶三北', NULL),
('19-03', '给排水', '周小伟', '刘涛', NULL),
('19-03', '钢构', NULL, NULL, NULL),
('19-03', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-05`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-05` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-05设计人员';

--
-- 转存表中的数据 `projectdesigner19-05`
--

INSERT INTO `projectdesigner19-05` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-05', 'BIM', NULL, NULL, NULL),
('19-05', 'PC', NULL, NULL, NULL),
('19-05', '中央空调', NULL, NULL, NULL),
('19-05', '亮丽', NULL, NULL, NULL),
('19-05', '其他', NULL, NULL, NULL),
('19-05', '内装', NULL, NULL, NULL),
('19-05', '内装机电', NULL, NULL, NULL),
('19-05', '基坑', NULL, NULL, NULL),
('19-05', '幕墙', NULL, NULL, NULL),
('19-05', '建筑', '胡刚', '林娜娜、薛德远、李海华', NULL),
('19-05', '弱电', '袁宏文', '沈进军', NULL),
('19-05', '总包', NULL, NULL, NULL),
('19-05', '护坡', NULL, NULL, NULL),
('19-05', '景观', NULL, NULL, NULL),
('19-05', '暖通', '高杰', '高杰', NULL),
('19-05', '燃气', NULL, NULL, NULL),
('19-05', '电力', NULL, NULL, NULL),
('19-05', '电气', '袁宏文', '鲍海萍、徐昌道', NULL),
('19-05', '管网', NULL, NULL, NULL),
('19-05', '结构', '郑敏', '陈方阳、郑敏、陈先塔、王昊、童达武', NULL),
('19-05', '给排水', '姜尔特', '姜尔特、金天豪', NULL),
('19-05', '钢构', NULL, NULL, NULL),
('19-05', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-06`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-06` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-06设计人员';

--
-- 转存表中的数据 `projectdesigner19-06`
--

INSERT INTO `projectdesigner19-06` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-06', 'BIM', NULL, NULL, NULL),
('19-06', 'PC', NULL, NULL, NULL),
('19-06', '中央空调', NULL, NULL, NULL),
('19-06', '亮丽', NULL, NULL, NULL),
('19-06', '其他', NULL, NULL, NULL),
('19-06', '内装', NULL, NULL, NULL),
('19-06', '内装机电', NULL, NULL, NULL),
('19-06', '基坑', NULL, NULL, NULL),
('19-06', '幕墙', NULL, NULL, NULL),
('19-06', '建筑', '杨衍', '张程东、朱张克、程孙剑、王海灿、周亮', NULL),
('19-06', '弱电', NULL, NULL, NULL),
('19-06', '总包', NULL, NULL, NULL),
('19-06', '护坡', NULL, NULL, NULL),
('19-06', '景观', NULL, NULL, NULL),
('19-06', '暖通', '高杰', '高杰', NULL),
('19-06', '燃气', NULL, NULL, NULL),
('19-06', '电力', NULL, NULL, NULL),
('19-06', '电气', '袁宏文', '朱锦锋、程晓君', NULL),
('19-06', '管网', NULL, NULL, NULL),
('19-06', '结构', '陈凡', '夏星星、陈凡、陈先塔、王昊、章建芒', NULL),
('19-06', '给排水', '周小伟', '周小伟、金天豪、曾翰进', NULL),
('19-06', '钢构', NULL, NULL, NULL),
('19-06', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-07`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-07` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-07设计人员';

--
-- 转存表中的数据 `projectdesigner19-07`
--

INSERT INTO `projectdesigner19-07` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-07', 'BIM', NULL, NULL, NULL),
('19-07', 'PC', NULL, NULL, NULL),
('19-07', '中央空调', NULL, NULL, NULL),
('19-07', '亮丽', NULL, NULL, NULL),
('19-07', '其他', NULL, NULL, NULL),
('19-07', '内装', NULL, NULL, NULL),
('19-07', '内装机电', NULL, NULL, NULL),
('19-07', '基坑', NULL, NULL, NULL),
('19-07', '幕墙', NULL, NULL, NULL),
('19-07', '建筑', '杨衍', '张晨翔、姚笃格、涂星琼', NULL),
('19-07', '弱电', '袁宏文', '沈进军', NULL),
('19-07', '总包', NULL, NULL, NULL),
('19-07', '护坡', NULL, NULL, NULL),
('19-07', '景观', NULL, NULL, NULL),
('19-07', '暖通', '高杰', '高杰', NULL),
('19-07', '燃气', NULL, NULL, NULL),
('19-07', '电力', NULL, NULL, NULL),
('19-07', '电气', '袁宏文', '程晓君', NULL),
('19-07', '管网', NULL, NULL, NULL),
('19-07', '结构', '郑敏', '王传辉、袁永洁、朱建武', NULL),
('19-07', '给排水', '周小伟', '周小伟', NULL),
('19-07', '钢构', NULL, NULL, NULL),
('19-07', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-08`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-08` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-08设计人员';

--
-- 转存表中的数据 `projectdesigner19-08`
--

INSERT INTO `projectdesigner19-08` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-08', 'BIM', NULL, NULL, NULL),
('19-08', 'PC', NULL, NULL, NULL),
('19-08', '中央空调', NULL, NULL, NULL),
('19-08', '亮丽', NULL, NULL, NULL),
('19-08', '其他', NULL, NULL, NULL),
('19-08', '内装', NULL, NULL, NULL),
('19-08', '内装机电', NULL, NULL, NULL),
('19-08', '基坑', NULL, NULL, NULL),
('19-08', '幕墙', NULL, NULL, NULL),
('19-08', '建筑', '胡刚', '薛德远、沈力航、陈阳、张鸣一、陈赛', NULL),
('19-08', '弱电', '袁宏文', '沈进军', NULL),
('19-08', '总包', NULL, NULL, NULL),
('19-08', '护坡', NULL, NULL, NULL),
('19-08', '景观', NULL, NULL, NULL),
('19-08', '暖通', '高杰', '高杰', NULL),
('19-08', '燃气', NULL, NULL, NULL),
('19-08', '电力', NULL, NULL, NULL),
('19-08', '电气', '袁宏文', '鲍海萍、徐昌道', NULL),
('19-08', '管网', NULL, NULL, NULL),
('19-08', '结构', '陈凡', '汪宇航、黄明周、林道春、吴婷婷、叶三北', NULL),
('19-08', '给排水', '姜尔特', '姜尔特、曾翰进、金天豪', NULL),
('19-08', '钢构', NULL, NULL, NULL),
('19-08', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-09`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-09` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-09设计人员';

--
-- 转存表中的数据 `projectdesigner19-09`
--

INSERT INTO `projectdesigner19-09` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-09', 'BIM', NULL, NULL, NULL),
('19-09', 'PC', NULL, NULL, NULL),
('19-09', '中央空调', NULL, NULL, NULL),
('19-09', '亮丽', NULL, NULL, NULL),
('19-09', '其他', NULL, NULL, NULL),
('19-09', '内装', NULL, NULL, NULL),
('19-09', '内装机电', NULL, NULL, NULL),
('19-09', '基坑', NULL, NULL, NULL),
('19-09', '幕墙', NULL, NULL, NULL),
('19-09', '建筑', '杨衍', '王海灿、程孙剑、张程东、郑晔、徐超、张晨翔、项往', NULL),
('19-09', '弱电', NULL, NULL, NULL),
('19-09', '总包', NULL, NULL, NULL),
('19-09', '护坡', NULL, NULL, NULL),
('19-09', '景观', NULL, NULL, NULL),
('19-09', '暖通', '高杰', '高杰', NULL),
('19-09', '燃气', NULL, NULL, NULL),
('19-09', '电力', NULL, NULL, NULL),
('19-09', '电气', '袁宏文', '朱锦锋、程晓君', NULL),
('19-09', '管网', NULL, NULL, NULL),
('19-09', '结构', '张晨', '张晨、陈方阳、袁永洁、叶三北、郑雄、夏业', NULL),
('19-09', '给排水', '姜尔特', '姜尔特、曾翰进、刘涛、金天豪', NULL),
('19-09', '钢构', NULL, NULL, NULL),
('19-09', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-10`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-10` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-10设计人员';

--
-- 转存表中的数据 `projectdesigner19-10`
--

INSERT INTO `projectdesigner19-10` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-10', 'BIM', NULL, NULL, NULL),
('19-10', 'PC', NULL, NULL, NULL),
('19-10', '中央空调', NULL, NULL, NULL),
('19-10', '亮丽', NULL, NULL, NULL),
('19-10', '其他', NULL, NULL, NULL),
('19-10', '内装', NULL, NULL, NULL),
('19-10', '内装机电', NULL, NULL, NULL),
('19-10', '基坑', NULL, NULL, NULL),
('19-10', '幕墙', NULL, NULL, NULL),
('19-10', '建筑', '胡刚', '沈琼宇、李凌培、杨洲', NULL),
('19-10', '弱电', NULL, NULL, NULL),
('19-10', '总包', NULL, NULL, NULL),
('19-10', '护坡', NULL, NULL, NULL),
('19-10', '景观', NULL, NULL, NULL),
('19-10', '暖通', '高杰', '高杰', NULL),
('19-10', '燃气', NULL, NULL, NULL),
('19-10', '电力', NULL, NULL, NULL),
('19-10', '电气', '程晓君', '程晓君', NULL),
('19-10', '管网', NULL, NULL, NULL),
('19-10', '结构', '陈凡', '童达武、夏星星、王昊、王传辉', NULL),
('19-10', '给排水', '周小伟', '周小伟', NULL),
('19-10', '钢构', NULL, NULL, NULL),
('19-10', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-11`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-11` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-11设计人员';

--
-- 转存表中的数据 `projectdesigner19-11`
--

INSERT INTO `projectdesigner19-11` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-11', 'BIM', NULL, NULL, NULL),
('19-11', 'PC', NULL, NULL, NULL),
('19-11', '中央空调', NULL, NULL, NULL),
('19-11', '亮丽', NULL, NULL, NULL),
('19-11', '其他', NULL, NULL, NULL),
('19-11', '内装', NULL, NULL, NULL),
('19-11', '内装机电', NULL, NULL, NULL),
('19-11', '基坑', NULL, NULL, NULL),
('19-11', '幕墙', NULL, NULL, NULL),
('19-11', '建筑', '胡刚', '陈赛、杨俏、李海华', NULL),
('19-11', '弱电', NULL, NULL, NULL),
('19-11', '总包', NULL, NULL, NULL),
('19-11', '护坡', NULL, NULL, NULL),
('19-11', '景观', NULL, NULL, NULL),
('19-11', '暖通', '高杰', '高杰', NULL),
('19-11', '燃气', NULL, NULL, NULL),
('19-11', '电力', NULL, NULL, NULL),
('19-11', '电气', '朱锦锋', '朱锦锋', NULL),
('19-11', '管网', NULL, NULL, NULL),
('19-11', '结构', '陈凡', '朱建武、袁永洁、陈凡', NULL),
('19-11', '给排水', '周小伟', '周小伟', NULL),
('19-11', '钢构', NULL, NULL, NULL),
('19-11', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-12`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-12` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-12设计人员';

--
-- 转存表中的数据 `projectdesigner19-12`
--

INSERT INTO `projectdesigner19-12` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-12', 'BIM', NULL, NULL, NULL),
('19-12', 'PC', NULL, NULL, NULL),
('19-12', '中央空调', NULL, NULL, NULL),
('19-12', '亮丽', NULL, NULL, NULL),
('19-12', '其他', NULL, NULL, NULL),
('19-12', '内装', NULL, NULL, NULL),
('19-12', '内装机电', NULL, NULL, NULL),
('19-12', '基坑', NULL, NULL, NULL),
('19-12', '幕墙', NULL, NULL, NULL),
('19-12', '建筑', '杨衍', '李敏、夏业、项往', NULL),
('19-12', '弱电', NULL, NULL, NULL),
('19-12', '总包', NULL, NULL, NULL),
('19-12', '护坡', NULL, NULL, NULL),
('19-12', '景观', NULL, NULL, NULL),
('19-12', '暖通', NULL, NULL, NULL),
('19-12', '燃气', NULL, NULL, NULL),
('19-12', '电力', NULL, NULL, NULL),
('19-12', '电气', NULL, NULL, NULL),
('19-12', '管网', NULL, NULL, NULL),
('19-12', '结构', NULL, NULL, NULL),
('19-12', '给排水', NULL, NULL, NULL),
('19-12', '钢构', NULL, NULL, NULL),
('19-12', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-13`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-13` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-13设计人员';

--
-- 转存表中的数据 `projectdesigner19-13`
--

INSERT INTO `projectdesigner19-13` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-13', 'BIM', NULL, NULL, NULL),
('19-13', 'PC', NULL, NULL, NULL),
('19-13', '中央空调', NULL, NULL, NULL),
('19-13', '亮丽', NULL, NULL, NULL),
('19-13', '其他', NULL, NULL, NULL),
('19-13', '内装', NULL, NULL, NULL),
('19-13', '内装机电', NULL, NULL, NULL),
('19-13', '基坑', NULL, NULL, NULL),
('19-13', '幕墙', NULL, NULL, NULL),
('19-13', '建筑', '杨衍', '张程东、周亮、朱张克', NULL),
('19-13', '弱电', '袁宏文', '沈进军', NULL),
('19-13', '总包', NULL, NULL, NULL),
('19-13', '护坡', NULL, NULL, NULL),
('19-13', '景观', NULL, NULL, NULL),
('19-13', '暖通', '高杰', '高杰', NULL),
('19-13', '燃气', NULL, NULL, NULL),
('19-13', '电力', NULL, NULL, NULL),
('19-13', '电气', '袁宏文', '鲍海萍、徐昌道', NULL),
('19-13', '管网', NULL, NULL, NULL),
('19-13', '结构', '张晨', '朱建武、钱波', NULL),
('19-13', '给排水', '周小伟', '周小伟', NULL),
('19-13', '钢构', NULL, NULL, NULL),
('19-13', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-15`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-15` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-15设计人员';

--
-- 转存表中的数据 `projectdesigner19-15`
--

INSERT INTO `projectdesigner19-15` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-15', 'BIM', NULL, NULL, NULL),
('19-15', 'PC', NULL, NULL, NULL),
('19-15', '中央空调', NULL, NULL, NULL),
('19-15', '亮丽', NULL, NULL, NULL),
('19-15', '其他', NULL, NULL, NULL),
('19-15', '内装', NULL, NULL, NULL),
('19-15', '内装机电', NULL, NULL, NULL),
('19-15', '基坑', NULL, NULL, NULL),
('19-15', '幕墙', NULL, NULL, NULL),
('19-15', '建筑', '胡刚', '沈琼宇、薛德远', NULL),
('19-15', '弱电', NULL, NULL, NULL),
('19-15', '总包', NULL, NULL, NULL),
('19-15', '护坡', NULL, NULL, NULL),
('19-15', '景观', NULL, NULL, NULL),
('19-15', '暖通', '高杰', '高杰', NULL),
('19-15', '燃气', NULL, NULL, NULL),
('19-15', '电力', NULL, NULL, NULL),
('19-15', '电气', NULL, NULL, NULL),
('19-15', '管网', NULL, NULL, NULL),
('19-15', '结构', '郑敏', '郑敏', NULL),
('19-15', '给排水', '姜尔特', '姜尔特', NULL),
('19-15', '钢构', NULL, NULL, NULL),
('19-15', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-16`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-16` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-16设计人员';

--
-- 转存表中的数据 `projectdesigner19-16`
--

INSERT INTO `projectdesigner19-16` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-16', 'BIM', NULL, NULL, NULL),
('19-16', 'PC', NULL, NULL, NULL),
('19-16', '中央空调', NULL, NULL, NULL),
('19-16', '亮丽', NULL, NULL, NULL),
('19-16', '其他', NULL, NULL, NULL),
('19-16', '内装', NULL, NULL, NULL),
('19-16', '内装机电', NULL, NULL, NULL),
('19-16', '基坑', NULL, NULL, NULL),
('19-16', '幕墙', NULL, NULL, NULL),
('19-16', '建筑', '胡刚', '林娜娜', NULL),
('19-16', '弱电', NULL, NULL, NULL),
('19-16', '总包', NULL, NULL, NULL),
('19-16', '护坡', NULL, NULL, NULL),
('19-16', '景观', NULL, NULL, NULL),
('19-16', '暖通', '高杰', '高杰', NULL),
('19-16', '燃气', NULL, NULL, NULL),
('19-16', '电力', NULL, NULL, NULL),
('19-16', '电气', '袁宏文', '鲍海萍、徐昌道', NULL),
('19-16', '管网', NULL, NULL, NULL),
('19-16', '结构', '陈凡', '王昊', NULL),
('19-16', '给排水', '周小伟', '周小伟', NULL),
('19-16', '钢构', NULL, NULL, NULL),
('19-16', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-17`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-17` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(20) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(100) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(100) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(100) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-17设计人员';

--
-- 转存表中的数据 `projectdesigner19-17`
--

INSERT INTO `projectdesigner19-17` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-17', 'BIM', NULL, NULL, NULL),
('19-17', 'PC', NULL, NULL, NULL),
('19-17', '中央空调', NULL, NULL, NULL),
('19-17', '亮丽', NULL, NULL, NULL),
('19-17', '其他', NULL, NULL, NULL),
('19-17', '内装', NULL, NULL, NULL),
('19-17', '内装机电', NULL, NULL, NULL),
('19-17', '基坑', NULL, NULL, NULL),
('19-17', '幕墙', NULL, NULL, NULL),
('19-17', '建筑', '杨衍', '涂星琼，李敏，姚笃格', ''),
('19-17', '弱电', '袁宏文', '沈进军', NULL),
('19-17', '总包', '杨衍', '杨衍', '无'),
('19-17', '护坡', NULL, NULL, NULL),
('19-17', '景观', NULL, NULL, NULL),
('19-17', '暖通', '高杰', '高杰', NULL),
('19-17', '燃气', NULL, NULL, NULL),
('19-17', '电力', NULL, NULL, NULL),
('19-17', '电气', '袁宏文', '鲍海萍', NULL),
('19-17', '管网', NULL, NULL, NULL),
('19-17', '结构', '郑敏', '陈方阳，黄明周，汪宇航，何瑞淼', ''),
('19-17', '给排水', '周小伟', '刘涛', NULL),
('19-17', '钢构', NULL, NULL, NULL),
('19-17', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-20`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-20` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-20设计人员';

--
-- 转存表中的数据 `projectdesigner19-20`
--

INSERT INTO `projectdesigner19-20` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-20', 'BIM', NULL, NULL, NULL),
('19-20', 'PC', NULL, NULL, NULL),
('19-20', '中央空调', NULL, NULL, NULL),
('19-20', '亮丽', NULL, NULL, NULL),
('19-20', '其他', NULL, NULL, NULL),
('19-20', '内装', NULL, NULL, NULL),
('19-20', '内装机电', NULL, NULL, NULL),
('19-20', '基坑', NULL, NULL, NULL),
('19-20', '幕墙', NULL, NULL, NULL),
('19-20', '建筑', '胡刚', '陈赛', NULL),
('19-20', '弱电', NULL, NULL, NULL),
('19-20', '总包', NULL, NULL, NULL),
('19-20', '护坡', NULL, NULL, NULL),
('19-20', '景观', NULL, NULL, NULL),
('19-20', '暖通', NULL, NULL, NULL),
('19-20', '燃气', NULL, NULL, NULL),
('19-20', '电力', NULL, NULL, NULL),
('19-20', '电气', NULL, NULL, NULL),
('19-20', '管网', NULL, NULL, NULL),
('19-20', '结构', NULL, NULL, NULL),
('19-20', '给排水', NULL, NULL, NULL),
('19-20', '钢构', NULL, NULL, NULL),
('19-20', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-108`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-108` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-108设计人员';

--
-- 转存表中的数据 `projectdesigner19-108`
--

INSERT INTO `projectdesigner19-108` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-108', 'BIM', NULL, NULL, NULL),
('19-108', 'PC', NULL, NULL, NULL),
('19-108', '中央空调', NULL, NULL, NULL),
('19-108', '亮丽', NULL, NULL, NULL),
('19-108', '其他', NULL, NULL, NULL),
('19-108', '内装', NULL, NULL, NULL),
('19-108', '内装机电', NULL, NULL, NULL),
('19-108', '基坑', NULL, NULL, NULL),
('19-108', '幕墙', NULL, NULL, NULL),
('19-108', '建筑', '胡刚', '徐坚一', NULL),
('19-108', '弱电', '袁宏文', '沈进军', NULL),
('19-108', '总包', '胡刚', '胡刚', NULL),
('19-108', '护坡', NULL, NULL, NULL),
('19-108', '景观', NULL, NULL, NULL),
('19-108', '暖通', '胡刚', '徐坚一', NULL),
('19-108', '燃气', NULL, NULL, NULL),
('19-108', '电力', NULL, NULL, NULL),
('19-108', '电气', '胡刚', '徐坚一', NULL),
('19-108', '管网', NULL, NULL, NULL),
('19-108', '结构', '吴晓', '李伊伊', NULL),
('19-108', '给排水', '张亮', '姜尔特', NULL),
('19-108', '钢构', NULL, NULL, NULL),
('19-108', '驳坎', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `projectdesigner19-109`
--

CREATE TABLE IF NOT EXISTS `projectdesigner19-109` (
  `project_id` varchar(30) NOT NULL COMMENT '工程编号',
  `project_design` varchar(200) NOT NULL COMMENT '设计内容',
  `projectdesigner_type` varchar(200) DEFAULT NULL COMMENT '工种负责人',
  `projectdesigner_design` varchar(200) DEFAULT NULL COMMENT '设计人',
  `project_subcontractor` varchar(200) DEFAULT NULL COMMENT '分包人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='19-109设计人员';

--
-- 转存表中的数据 `projectdesigner19-109`
--

INSERT INTO `projectdesigner19-109` (`project_id`, `project_design`, `projectdesigner_type`, `projectdesigner_design`, `project_subcontractor`) VALUES
('19-109', 'BIM', NULL, NULL, NULL),
('19-109', 'PC', NULL, NULL, NULL),
('19-109', '中央空调', NULL, NULL, NULL),
('19-109', '亮丽', NULL, NULL, NULL),
('19-109', '其他', NULL, NULL, NULL),
('19-109', '内装', NULL, NULL, NULL),
('19-109', '内装机电', NULL, NULL, NULL),
('19-109', '基坑', NULL, NULL, NULL),
('19-109', '幕墙', NULL, NULL, NULL),
('19-109', '建筑', '胡刚', '周梦迪', NULL),
('19-109', '弱电', '袁宏文', '沈进军', NULL),
('19-109', '总包', NULL, NULL, NULL),
('19-109', '护坡', NULL, NULL, NULL),
('19-109', '景观', NULL, NULL, NULL),
('19-109', '暖通', '高杰', '高杰', NULL),
('19-109', '燃气', NULL, NULL, NULL),
('19-109', '电力', NULL, NULL, NULL),
('19-109', '电气', '袁宏文', '鲍海萍', NULL),
('19-109', '管网', NULL, NULL, NULL),
('19-109', '结构', '张晨', '黄明周', NULL),
('19-109', '给排水', '周小伟', '周小伟', NULL),
('19-109', '钢构', NULL, NULL, NULL),
('19-109', '驳坎', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `green_administrators`
--
ALTER TABLE `green_administrators`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `green_architecture_fee`
--
ALTER TABLE `green_architecture_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_bid`
--
ALTER TABLE `green_bid`
  ADD PRIMARY KEY (`toubiao_id`);

--
-- Indexes for table `green_bidcompensation`
--
ALTER TABLE `green_bidcompensation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_biddeposite`
--
ALTER TABLE `green_biddeposite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_bidphase`
--
ALTER TABLE `green_bidphase`
  ADD PRIMARY KEY (`toubiao_id`);

--
-- Indexes for table `green_confirm`
--
ALTER TABLE `green_confirm`
  ADD PRIMARY KEY (`confirm_id`);

--
-- Indexes for table `green_contract`
--
ALTER TABLE `green_contract`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_contractaccountphase`
--
ALTER TABLE `green_contractaccountphase`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `green_contractconstruction`
--
ALTER TABLE `green_contractconstruction`
  ADD PRIMARY KEY (`contract_id`,`contract_design`);

--
-- Indexes for table `green_contractledger`
--
ALTER TABLE `green_contractledger`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `green_contractphase`
--
ALTER TABLE `green_contractphase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_contractsettlement`
--
ALTER TABLE `green_contractsettlement`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `green_contractunitprice`
--
ALTER TABLE `green_contractunitprice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_customer`
--
ALTER TABLE `green_customer`
  ADD PRIMARY KEY (`project_id`,`customer_name`),
  ADD KEY `customer_contractname` (`project_name`),
  ADD KEY `customer_company` (`customer_company`),
  ADD KEY `customer_email` (`customer_email`),
  ADD KEY `customer_QQ` (`customer_QQ`),
  ADD KEY `customer_wechat` (`customer_wechat`),
  ADD KEY `customer_mobile` (`customer_mobile`),
  ADD KEY `customer_phone` (`customer_phone`);

--
-- Indexes for table `green_departmentvalue`
--
ALTER TABLE `green_departmentvalue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_drawing_fee`
--
ALTER TABLE `green_drawing_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_drawplan_designer`
--
ALTER TABLE `green_drawplan_designer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_economic_indicators`
--
ALTER TABLE `green_economic_indicators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_equipment_fee`
--
ALTER TABLE `green_equipment_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_ledgernode`
--
ALTER TABLE `green_ledgernode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_personalvalue`
--
ALTER TABLE `green_personalvalue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `output_value` (`output_value`);

--
-- Indexes for table `green_personalvalue林娜娜、李凌培`
--
ALTER TABLE `green_personalvalue林娜娜、李凌培`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_personalvalue王宇飞`
--
ALTER TABLE `green_personalvalue王宇飞`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_personalvalue胡刚`
--
ALTER TABLE `green_personalvalue胡刚`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_personalvalue陈赛`
--
ALTER TABLE `green_personalvalue陈赛`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_project`
--
ALTER TABLE `green_project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `project_name` (`project_name`),
  ADD KEY `project_contractor` (`project_contractor`(255)),
  ADD KEY `project_agent` (`project_agent`(255)),
  ADD KEY `project_leader` (`project_leader`(255));

--
-- Indexes for table `green_projectconstruction`
--
ALTER TABLE `green_projectconstruction`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `green_projectdraw`
--
ALTER TABLE `green_projectdraw`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `green_projectdrawplan`
--
ALTER TABLE `green_projectdrawplan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectphase`
--
ALTER TABLE `green_projectphase`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `green_projectvalue`
--
ALTER TABLE `green_projectvalue`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `green_projectvalue17-13`
--
ALTER TABLE `green_projectvalue17-13`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-01`
--
ALTER TABLE `green_projectvalue19-01`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-02`
--
ALTER TABLE `green_projectvalue19-02`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-03`
--
ALTER TABLE `green_projectvalue19-03`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-05`
--
ALTER TABLE `green_projectvalue19-05`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-06`
--
ALTER TABLE `green_projectvalue19-06`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-07`
--
ALTER TABLE `green_projectvalue19-07`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-08`
--
ALTER TABLE `green_projectvalue19-08`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-09`
--
ALTER TABLE `green_projectvalue19-09`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-10`
--
ALTER TABLE `green_projectvalue19-10`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-11`
--
ALTER TABLE `green_projectvalue19-11`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-12`
--
ALTER TABLE `green_projectvalue19-12`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-13`
--
ALTER TABLE `green_projectvalue19-13`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-15`
--
ALTER TABLE `green_projectvalue19-15`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-16`
--
ALTER TABLE `green_projectvalue19-16`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-17`
--
ALTER TABLE `green_projectvalue19-17`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-20`
--
ALTER TABLE `green_projectvalue19-20`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-108`
--
ALTER TABLE `green_projectvalue19-108`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvalue19-109`
--
ALTER TABLE `green_projectvalue19-109`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_projectvaluemajor`
--
ALTER TABLE `green_projectvaluemajor`
  ADD PRIMARY KEY (`project_id`,`major_number`);

--
-- Indexes for table `green_project_totalvalue`
--
ALTER TABLE `green_project_totalvalue`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `green_staff`
--
ALTER TABLE `green_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `green_staffdepartment`
--
ALTER TABLE `green_staffdepartment`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `green_structure_fee`
--
ALTER TABLE `green_structure_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_tosum`
--
ALTER TABLE `green_tosum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `green_tosum17-13`
--
ALTER TABLE `green_tosum17-13`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectdesigner17-13`
--
ALTER TABLE `projectdesigner17-13`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-01`
--
ALTER TABLE `projectdesigner19-01`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-02`
--
ALTER TABLE `projectdesigner19-02`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-03`
--
ALTER TABLE `projectdesigner19-03`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-05`
--
ALTER TABLE `projectdesigner19-05`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-06`
--
ALTER TABLE `projectdesigner19-06`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-07`
--
ALTER TABLE `projectdesigner19-07`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-08`
--
ALTER TABLE `projectdesigner19-08`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-09`
--
ALTER TABLE `projectdesigner19-09`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-10`
--
ALTER TABLE `projectdesigner19-10`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-11`
--
ALTER TABLE `projectdesigner19-11`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-12`
--
ALTER TABLE `projectdesigner19-12`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-13`
--
ALTER TABLE `projectdesigner19-13`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-15`
--
ALTER TABLE `projectdesigner19-15`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-16`
--
ALTER TABLE `projectdesigner19-16`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-17`
--
ALTER TABLE `projectdesigner19-17`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-20`
--
ALTER TABLE `projectdesigner19-20`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-108`
--
ALTER TABLE `projectdesigner19-108`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- Indexes for table `projectdesigner19-109`
--
ALTER TABLE `projectdesigner19-109`
  ADD PRIMARY KEY (`project_id`,`project_design`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `green_administrators`
--
ALTER TABLE `green_administrators`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `green_architecture_fee`
--
ALTER TABLE `green_architecture_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `green_bidcompensation`
--
ALTER TABLE `green_bidcompensation`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `green_biddeposite`
--
ALTER TABLE `green_biddeposite`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `green_confirm`
--
ALTER TABLE `green_confirm`
  MODIFY `confirm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '发票编号';
--
-- AUTO_INCREMENT for table `green_contract`
--
ALTER TABLE `green_contract`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `green_contractphase`
--
ALTER TABLE `green_contractphase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `green_contractunitprice`
--
ALTER TABLE `green_contractunitprice`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `green_departmentvalue`
--
ALTER TABLE `green_departmentvalue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `green_drawing_fee`
--
ALTER TABLE `green_drawing_fee`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `green_drawplan_designer`
--
ALTER TABLE `green_drawplan_designer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `green_economic_indicators`
--
ALTER TABLE `green_economic_indicators`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `green_equipment_fee`
--
ALTER TABLE `green_equipment_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `green_ledgernode`
--
ALTER TABLE `green_ledgernode`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `green_personalvalue`
--
ALTER TABLE `green_personalvalue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `green_personalvalue林娜娜、李凌培`
--
ALTER TABLE `green_personalvalue林娜娜、李凌培`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `green_personalvalue王宇飞`
--
ALTER TABLE `green_personalvalue王宇飞`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `green_personalvalue胡刚`
--
ALTER TABLE `green_personalvalue胡刚`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `green_personalvalue陈赛`
--
ALTER TABLE `green_personalvalue陈赛`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `green_projectdrawplan`
--
ALTER TABLE `green_projectdrawplan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `green_projectvalue`
--
ALTER TABLE `green_projectvalue`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue17-13`
--
ALTER TABLE `green_projectvalue17-13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `green_projectvalue19-01`
--
ALTER TABLE `green_projectvalue19-01`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-02`
--
ALTER TABLE `green_projectvalue19-02`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-03`
--
ALTER TABLE `green_projectvalue19-03`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-05`
--
ALTER TABLE `green_projectvalue19-05`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-06`
--
ALTER TABLE `green_projectvalue19-06`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-07`
--
ALTER TABLE `green_projectvalue19-07`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-08`
--
ALTER TABLE `green_projectvalue19-08`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-09`
--
ALTER TABLE `green_projectvalue19-09`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-10`
--
ALTER TABLE `green_projectvalue19-10`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-11`
--
ALTER TABLE `green_projectvalue19-11`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-12`
--
ALTER TABLE `green_projectvalue19-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-13`
--
ALTER TABLE `green_projectvalue19-13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-15`
--
ALTER TABLE `green_projectvalue19-15`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-16`
--
ALTER TABLE `green_projectvalue19-16`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `green_projectvalue19-17`
--
ALTER TABLE `green_projectvalue19-17`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-20`
--
ALTER TABLE `green_projectvalue19-20`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-108`
--
ALTER TABLE `green_projectvalue19-108`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_projectvalue19-109`
--
ALTER TABLE `green_projectvalue19-109`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `green_staff`
--
ALTER TABLE `green_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `green_staffdepartment`
--
ALTER TABLE `green_staffdepartment`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '部门编号',AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `green_structure_fee`
--
ALTER TABLE `green_structure_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',AUTO_INCREMENT=349;
--
-- AUTO_INCREMENT for table `green_tosum`
--
ALTER TABLE `green_tosum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号';
--
-- AUTO_INCREMENT for table `green_tosum17-13`
--
ALTER TABLE `green_tosum17-13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
