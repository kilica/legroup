## /////////////////////// — XCL : : Web Application Platform

**Module Name**  : Legroup 

**Module Version**  : 2.3.x  

**Description** : This module allows you to manage users, groups, and roles.

---
  

##### :computer: The Minimum Requirements


          Apache, Nginx, etc.
          PHP 7.2.x
          MySQL 5.6, MariaDB
          InnoDB utf8 / utf8mb4
          XCL 2.3.x
          
          
Legroup
=======
Legroup is a module to manage users, groups and roles (membership).    
Users can create own groups, public or private like SNS,      
and allow other users to request a membership to a group and managers.  
Legroup can extend and work together with other modules.     
The Legroup module features a combinated modules' data about the group.

## Environment

XOOPS Cube Legacy 2.2 or later XCL 2.3.x

## Main Features

* Create groups
* User can join in groups as a member
* Member rank (guest/associate/regular/staff/owner)
* Member management by group staffs
* Permission policy management by group owner
* Add group image (requires LEImg module)

# Update History

0.21.1 (2021.03.10) gigamaster
* Fix Smarty template
* Fix Notice and Warning
* SQL varchar from 255 to 191  
  
0.21 (2021.03.01) gigamaster
* Fix class handler Group
* Fix class handler Member
* Fix missing constant english
* Fix smarty template group view 

0.20 (2012.08.28)
* Fix Bugs
