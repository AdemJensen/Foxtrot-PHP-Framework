# Foxtrot-PHP-Framework
The foxtrot PHP framework, built specialy for Cloud OJ.

## Introduction
**Attention: This project is not finished yet, this version is only a inner test version, CANNOT be used in production environment!**

Hello, and welcome to use the PHP framework Foxtrot. 

The Foxtrot framework is a half-standard MVC framework, which means that it contains come of the basic functions of MVC structure, but it wasn't restricted too much.

In that framework, not only can you use MVC programming, but also the object-oriented programming.

(Developing feature, will release on version 1.0) Aside from above, the Building Block System is another feature. The whole system is designed for community, so does all the products comes form this framework. The system only contains the kernel and Building Blocks. All the functions, even the main page can be a Building Block!

This project is based on PHP7.2, It's recommended to use Ubuntu18.04, MySQL5.7 and Apache2.

## Getting started

### Installtion
To install the Foxtrot Framework, you need to install Apache2, PHP7.2 and MySQL5.7.

And then, you need to load the **rewrite** module for Apache2 to support the routing function.

Copy the whole directory to the document root, make sure the index.php is on the root directory.

If you meet routing issues later, try to chage the AllowOverride to 'All' in the file '/etc/apache2/apache2.conf'

### Dev guide
#### Life circle
The system life circle begins from index.php, where a kernel object is created.

The kernel contains usful const values, and loads all the classes, controllers and auxiliary files. During this process, a File Operation Supportive auxiliary util will be loaded first to support other loadings.

When modules are loaded, the router will be created and register all the routing informations.

Then the system will process user request: analysed by the router, and assign them to differnet controllers on different methods, and controllers will invoke Models and Views to generate requests for user. During that time, supportive classes can be used during the assignments and invokings.

#### Building Blocks
Different from traditional programming, all the changes are required to be made as Building Blocks. Including new pages, user systems, etc. 

Building blocks can be installed on the 'Slots' provided by kernel or other Building Blocks.

To manage the building blocks, you can use ECSM (Efficient Central System Manager).

======Detailed informations are pending======

#### (Developing feature, will release on version 1.0) ECSM
The ECSM (Efficient Central System Manager) is designed to monitor the Foxtrot Applications, providing the server status, Access history, building Block management, Routing management, etc.

**This is a developing feature, will release on version 1.0**

======Detailed informations are pending======

## About coding
This is a MSC project code, being the foundation of the upcomming online judging system **Cloud OJ**. This code is published along with the OJ system in the spirit of sharing and better be useful.

If you have any questions, just make "issues" or "pull requests".

Stars, Follows, come on!

## About author
The author is a college student (1st Grade at the year of 2018).

If you truly love my work and wants to help me, please make a donation. I appreciate your generous help!

My alipay account is: 15553142784, thank you!

## Licence
This project is shared under the licence of General Public Licence 3.0 (GPL3.0).

The details are in the LICENSE file. If you attempt to use them for other uses, please read them first, thank you.
