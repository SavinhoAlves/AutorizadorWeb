/*
 Navicat Premium Data Transfer

 Source Server         : AutorizadorHM
 Source Server Type    : MySQL
 Source Server Version : 100427
 Source Host           : localhost:3306
 Source Schema         : amigohmdb

 Target Server Type    : MySQL
 Target Server Version : 100427
 File Encoding         : 65001

 Date: 10/06/2024 02:23:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for beneficiarios
-- ----------------------------
DROP TABLE IF EXISTS `beneficiarios`;
CREATE TABLE `beneficiarios`  (
  `id` bigint NOT NULL,
  `matricula` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `cpf` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `nome` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `data_nascim` date NULL DEFAULT NULL,
  `idade` int NULL DEFAULT NULL,
  `titular_dep` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `ativo` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of beneficiarios
-- ----------------------------

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL,
  `is_read` int NULL DEFAULT NULL,
  `created_at` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES (1, 1, 'Teste', 'teste', 1, '2024-06-10');
INSERT INTO `notifications` VALUES (4, 1, '', 'Nova mensagem recebida', NULL, '2024-06-04');
INSERT INTO `notifications` VALUES (5, 1, '', 'Atualização do sistema', NULL, '2024-06-04');
INSERT INTO `notifications` VALUES (6, 1, '', 'Novo comentário em sua postagem', NULL, '2024-06-04');
INSERT INTO `notifications` VALUES (7, 1, 'teste 1', 'teste 1', 1, '2024-06-10');
INSERT INTO `notifications` VALUES (8, 1, 'Teste 2', 'Teste 2', 0, '2024-06-10');
INSERT INTO `notifications` VALUES (9, 1, 'Teste 3', 'Teste 3', 0, '2024-06-10');
INSERT INTO `notifications` VALUES (10, 1, 'Teste 4', 'Teste 4', 0, '2024-06-10');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint NOT NULL,
  `username` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `password` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  `role` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (4, 'Admin', '$2y$10$YfS2jPS1zq9P53HR7Vr8z.l7LVMz645y8zaZbHWIZZjOUz0jxIBQS', 'admin');

SET FOREIGN_KEY_CHECKS = 1;
