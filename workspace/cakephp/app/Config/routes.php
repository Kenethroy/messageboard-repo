<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
/**Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/users/register', array('controller' => 'users', 'action' => 'register'));
*/
Router::connect('users/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('users/register', array('controller' => 'users', 'action' => 'register'));
Router::connect('users/editProfile', array('controller' => 'users', 'action' => 'editProfile'));
Router::connect('/users/search', ['controller' => 'users', 'action' => 'search']);
Router::connect('users/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('users/viewProfile', array('controller' => 'users', 'action' => 'viewProfile'));
Router::connect('/users/changeEmailPassword', ['controller' => 'users', 'action' => 'changeEmailPassword']);


Router::connect('/conversations', array('controller' => 'conversations', 'action' => 'index'));	
Router::connect('/conversations/view/:id', array('controller' => 'conversations', 'action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));
 
/** 
*Router::connect('/contacts/index', array('controller' => 'contacts', 'action' => 'index'));
*Router::connect('/contacts/add', array('controller' => 'contacts', 'action' => 'add'));
*Router::connect('/contacts/edit/:id', array('controller' => 'contacts', 'action' => 'edit'), array('pass' => array('id'), 'id' => '[0-9]+'));
*Router::connect('/contacts/view/:id', array('controller' => 'contacts', 'action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));
*/

	 
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
