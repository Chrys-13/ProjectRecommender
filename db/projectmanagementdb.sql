SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE `projectmanagementdb`;
CREATE DATABASE IF NOT EXISTS `projectmanagementdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `projectmanagementdb`;

DROP TABLE IF EXISTS `discussion_forums`;
CREATE TABLE `discussion_forums` (
  `forum_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `discussion_forums` (`forum_id`, `project_id`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 'Example Forum', '2024-06-30 21:22:54', '2024-06-30 21:22:54');

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `feedback` (`feedback_id`, `user_id`, `project_id`, `rating`, `comments`, `created_at`) VALUES
(2, 1, 1, 5, 'Great project!', '2024-06-30 21:05:30');

DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE `forum_posts` (
  `post_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `forum_posts` (`post_id`, `forum_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'This is a test post.', '2024-06-30 21:23:26', '2024-06-30 21:23:26');

DROP TABLE IF EXISTS `learning_paths`;
CREATE TABLE `learning_paths` (
  `learning_path_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_sequence` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`project_sequence`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL,
  `estimated_time` int(11) DEFAULT NULL,
  `prerequisites` text DEFAULT NULL,
  `resources` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `projects` (`project_id`, `title`, `description`, `difficulty`, `estimated_time`, `prerequisites`, `resources`, `created_at`, `updated_at`) VALUES
(1, 'Updated Project', 'Updated Description', 'advanced', 10, 'Advanced Knowledge', 'URL3,URL4', '2024-06-30 20:15:21', '2024-07-01 16:21:49'),
(4, 'Updated Project', 'Updated Description', 'advanced', 10, 'Advanced Knowledge', 'URL3,URL4', '2024-07-01 16:20:43', '2024-07-01 16:22:31'),
(6, 'Recent Insert', 'Recently Inserted Description', 'intermediate', 0, 'A lot, brev', 'Nada', '2024-07-02 12:27:55', '2024-07-02 12:41:11');

DROP TABLE IF EXISTS `project_metadata`;
CREATE TABLE `project_metadata` (
  `metadata_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_metadata` (`metadata_id`, `project_id`, `key`, `value`) VALUES
(3, 1, 'language', 'JavaScript'),
(4, 1, 'library', 'React'),
(5, 4, 'language', 'JavaScript'),
(6, 4, 'library', 'React');

DROP TABLE IF EXISTS `project_tags`;
CREATE TABLE `project_tags` (
  `project_tag_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_tags` (`project_tag_id`, `project_id`, `tag_id`) VALUES
(3, 6, 1),
(4, 6, 2),
(5, 6, 3);

DROP TABLE IF EXISTS `recommendations`;
CREATE TABLE `recommendations` (
  `recommendation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `recommended_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tags` (`tag_id`, `tag_name`) VALUES
(1, 'ExampleTag'),
(2, 'Project Management'),
(3, 'Software Engineering');

DROP TABLE IF EXISTS `tag_synonyms`;
CREATE TABLE `tag_synonyms` (
  `synonym_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `synonym` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tag_synonyms` (`synonym_id`, `tag_id`, `synonym`) VALUES
(1, 1, 'SampleSynonym');

DROP TABLE IF EXISTS `usage_metrics`;
CREATE TABLE `usage_metrics` (
  `metric_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `metric_key` varchar(100) NOT NULL,
  `metric_value` text NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usage_metrics` (`metric_id`, `user_id`, `metric_key`, `metric_value`, `recorded_at`) VALUES
(1, 1, 'page_views', '10', '2024-07-01 16:45:49');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'elikem', '$2y$10$qQVXfoCLwL6ZipTR1GZJHeePf2gmreEjWwUdXK27yILFqJXdNVlka', 'egalzoyiku@gmail.com', '2024-06-30 18:06:09', '2024-06-30 18:06:09'),
(2, 'joshua', '$2y$10$7Wg.i/yr3/j48AgBiJfBG.tbzfL6nb8w.OYDW8qlIQpXFj3oql87q', 'joshuaagyemang08@gmail.com', '2024-06-30 18:14:32', '2024-06-30 18:14:32'),
(6, 'testuser', '$2y$10$6stjJfYLsbofrrL7jCo24eAKzrpj1zL4N7ceePiAnv82CLek6ITS.', 'elikem@elkem.info', '2024-07-02 19:16:53', '2024-07-02 19:16:53');
DROP TRIGGER IF EXISTS `after_user_insert`;
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    INSERT INTO user_profiles (user_id, interests, skills, goals, current_skill_level)
    VALUES (NEW.user_id, '[]', '', '', '');
END
$$
DELIMITER ;

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interests` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`interests`)),
  `skills` text DEFAULT NULL,
  `goals` text DEFAULT NULL,
  `current_skill_level` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_profiles` (`profile_id`, `user_id`, `interests`, `skills`, `goals`, `current_skill_level`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"coding\",\"gaming\"]', 'PHP, JavaScript', 'Become a full-stack developer', 'intermediate', '2024-06-30 18:06:09', '2024-06-30 19:42:51'),
(2, 2, '[\"coding\",\"gaming\"]', 'PHP, JavaScript', 'Become a full-stack developer', 'intermediate', '2024-06-30 18:14:32', '2024-06-30 19:43:15'),
(6, 6, '[]', '', '', '', '2024-07-02 19:16:53', '2024-07-02 19:16:53');

DROP TABLE IF EXISTS `user_progress`;
CREATE TABLE `user_progress` (
  `progress_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `progress_percentage` decimal(5,2) DEFAULT NULL CHECK (`progress_percentage` >= 0 and `progress_percentage` <= 100),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_progress` (`progress_id`, `user_id`, `project_id`, `progress_percentage`, `updated_at`) VALUES
(1, 1, 1, 100.00, '2024-07-02 13:01:28'),
(2, 2, 1, 100.00, '2024-07-02 13:02:16');

DROP TABLE IF EXISTS `user_projects`;
CREATE TABLE `user_projects` (
  `user_project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('in-progress','completed') NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_projects` (`user_project_id`, `user_id`, `project_id`, `status`, `started_at`, `completed_at`, `feedback`) VALUES
(1, 1, 1, 'completed', '2024-07-02 12:29:14', '2024-07-02 13:01:28', 'Great project!'),
(3, 2, 1, 'completed', '2024-07-02 12:55:11', '2024-07-02 13:02:16', 'Great project!');
DROP TRIGGER IF EXISTS `insert_user_progress_on_assignment`;
DELIMITER $$
CREATE TRIGGER `insert_user_progress_on_assignment` AFTER INSERT ON `user_projects` FOR EACH ROW BEGIN
    INSERT INTO user_progress (user_id, project_id, progress_percentage)
    VALUES (NEW.user_id, NEW.project_id, 0);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_user_progress_on_completion`;
DELIMITER $$
CREATE TRIGGER `update_user_progress_on_completion` AFTER UPDATE ON `user_projects` FOR EACH ROW BEGIN
    IF NEW.status = 'completed' THEN
        UPDATE user_progress
        SET progress_percentage = 100
        WHERE user_id = NEW.user_id AND project_id = NEW.project_id;
    END IF;
END
$$
DELIMITER ;


ALTER TABLE `discussion_forums`
  ADD PRIMARY KEY (`forum_id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `learning_paths`
  ADD PRIMARY KEY (`learning_path_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

ALTER TABLE `project_metadata`
  ADD PRIMARY KEY (`metadata_id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `project_tags`
  ADD PRIMARY KEY (`project_tag_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `tag_id` (`tag_id`);

ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`recommendation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);

ALTER TABLE `tag_synonyms`
  ADD PRIMARY KEY (`synonym_id`),
  ADD KEY `tag_id` (`tag_id`);

ALTER TABLE `usage_metrics`
  ADD PRIMARY KEY (`metric_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

ALTER TABLE `user_projects`
  ADD PRIMARY KEY (`user_project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);


ALTER TABLE `discussion_forums`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `forum_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `learning_paths`
  MODIFY `learning_path_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `project_metadata`
  MODIFY `metadata_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `project_tags`
  MODIFY `project_tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `recommendations`
  MODIFY `recommendation_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tag_synonyms`
  MODIFY `synonym_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `usage_metrics`
  MODIFY `metric_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `user_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `user_projects`
  MODIFY `user_project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `discussion_forums`
  ADD CONSTRAINT `discussion_forums_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `discussion_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `learning_paths`
  ADD CONSTRAINT `learning_paths_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `project_metadata`
  ADD CONSTRAINT `project_metadata_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `project_tags`
  ADD CONSTRAINT `project_tags_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tag_synonyms`
  ADD CONSTRAINT `tag_synonyms_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `usage_metrics`
  ADD CONSTRAINT `usage_metrics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_projects`
  ADD CONSTRAINT `user_projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
