SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cragbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `areaid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `location` varchar(50) COLLATE utf8_bin NOT NULL,
  `public` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `crags`
--

CREATE TABLE `crags` (
  `cragid` int(11) NOT NULL,
  `areaid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `approach` text COLLATE utf8_bin NOT NULL,
  `access` text COLLATE utf8_bin NOT NULL,
  `policy` text COLLATE utf8_bin NOT NULL,
  `location` varchar(50) COLLATE utf8_bin NOT NULL,
  `public` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `routeid` int(11) NOT NULL,
  `cragid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `grade` varchar(50) COLLATE utf8_bin NOT NULL,
  `stars` varchar(5) COLLATE utf8_bin NOT NULL,
  `length` int(11) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `sector` varchar(255) COLLATE utf8_bin NOT NULL,
  `discipline` int(11) NOT NULL,
  `firstascent` varchar(255) COLLATE utf8_bin NOT NULL,
  `orderid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE `site` (
  `id` int(11) NOT NULL,
  `setting` varchar(100) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `setting`, `value`) VALUES
(1, 'home_text', '&lt;h2&gt;Welcome to Cragbook!&lt;/h2&gt;&lt;p&gt;Cragbook lets you you create and share rock climbing guides to crags in your local areas.&lt;/p&gt;&lt;p&gt;Designed to be simple to use, Cragbook utilises a clean look and feel to help you get started quickly.&lt;/p&gt;&lt;p&gt;Cragbook is&amp;nbsp;licensed under the GNU General Public License v3 and is free for anyone to use, modify or share.&lt;/p&gt;&lt;h3&gt;Getting Started&amp;nbsp;&lt;/h3&gt;&lt;p&gt;Above, you will see&amp;nbsp;Areas and Crags.&lt;/p&gt;&lt;p&gt;Areas will give you an overview of all the climbing areas on your site. Inside each area you will find crags for&amp;nbsp;that area, which will eventually contain the route information.&lt;/p&gt;&lt;p&gt;The Crags page above will show you all of the crags on your site, if you so wish.&lt;/p&gt;&lt;p&gt;Each overview, area or crag, will also give you an option to view where the place is by clicking on the map icon.&lt;/p&gt;&lt;p&gt;Login, and create a new area to get started.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;Enjoy!&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` text COLLATE utf8_bin NOT NULL,
  `displayname` varchar(100) COLLATE utf8_bin NOT NULL,
  `groupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `displayname`, `groupid`) VALUES
(1, 'admin', '$2y$10$b2YmGKaEA2w.KntApuv42eNZbUjqwik2X.nNhnZzSqezWxsWb52Fy', 'Administrator', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`areaid`),
  ADD UNIQUE KEY `name_2` (`name`);

--
-- Indexes for table `crags`
--
ALTER TABLE `crags`
  ADD PRIMARY KEY (`cragid`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`routeid`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `areaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `crags`
--
ALTER TABLE `crags`
  MODIFY `cragid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `routeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
