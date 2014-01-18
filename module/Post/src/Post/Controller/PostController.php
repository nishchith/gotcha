<?php

namespace Post\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

use Core\Uri;

class PostController extends AbstractActionController
{
	protected $postTable;

	public function getPostTable()
	{
		// $this->getServiceLocator()->get('Post\Model\PostTable');
		if (!$this->postTable) {
			$sm = $this->getServiceLocator();
			$this->postTable = $sm->get('Post\Model\PostTable');
		}
		return $this->postTable;
	}

	public function indexAction()
	{
		return new JsonModel(array(
			'RESPONSE' => "Welcome to DutchLady. You are seeing this message probably beause you have hit a wrong URL! Please contact our service team.",
			));
	}

	public function viewAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$post = array(
				'id'	=> $params->cloudId,
				); 

			$row = $this->getPostTable()->fetchPost($post['id']); //if apporved

			if ($row)
			{
				$result = array(
					'status' => true,
					'msg' => "Success",
					'data' => $row,
					);
			} 
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Post Does Not Exist",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function getBulkPostAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$post = array(
				'index'	=> $params->cloudId,
				'count'	=> $params->count,
				'type'	=> $params->type, // popular|latest|featured|image|video|keyword
				); 

			$rowSet = $this->getPostTable()->fetchAll($post); //if apporved

			if ($rowSet)
			{
				$result = array(
					'status' => true,
					'msg' => "Success",
					'data' => $rowSet,
					);
			} 
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Post Does Not Exist",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}
	public function submitAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$serverTime = time()*1000;

			$post = array(
				'title' => $params->title,
				'description' => $params->description,
				'fileType' => $params->fileType,
				'filePath' => $params->filePath,
				'status' => 'PFA',
				'dateAdded' => $serverTime,
				'dateLastUpdated' => $serverTime,
				'active' => true,
				); 

			// youtube private post goes here

			$postId = $this->getUserTable()->insert($post);

			if ($postId)
			{
				$link = array(
					'childId' => $params->childId,
					'postId' => $postId,
					);

				$linkId = $this->getUserTable()->insertLinkToChildId($link);

				if ($linkId)
				{

					$result = array(
						'status' => true,
						'msg' => "Submitted Successfully",
						'cloudId' => $postId,
						);
				}
				else
				{
					$result = array(
						'status' => false,
						'msg' => "Linking Failed. Try Again Later",
						);
				}
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Submit Failed. Try Again Later",
					);
			}
		}
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function deleteAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$post = array(
				'id'	=> $params->cloudId,
				); 

			$row = $this->getPostTable()->fetchPost($post['id']); 

			if ($row)
			{
				$postStatus = $this->getUserTable()->delete($post['id']);

				if ($postStatus)
				{
					$linkStatus = $this->getUserTable()->deleteLink($post['id']);

					if ($linkStatus)
					{
						$result = array(
							'status' => true,
							'msg' => "Post Deleted Successfully",
							'cloudId' => $postId,
							);
					}
					else
					{
						$result = array(
							'status' => false,
							'msg' => "Unlinking Failed.",
							);
					}
				}
				else
				{
					$result = array(
						'status' => false,
						'msg' => "Post Deletion Failed. Try Again Later",
						);
				}
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Can Not Find The Post",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function likeAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$post = array(
				'id'	=> $params->cloudId,
				); 

			$row = $this->getPostTable()->fetchPost($post['id']); 

			if ($row)
			{
				$postStatus = $this->getUserTable()->like($post['id']);

				if ($postStatus)
				{
					$linkStatus = $this->getUserTable()->deleteLink($post['id']);

					$result = array(
						'status' => true,
						'msg' => "Success",
						'cloudId' => $postId,
						);
				}
				else
				{
					$result = array(
						'status' => false,
						'msg' => "Fail",
						);
				}
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Can Not Find The Post",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function shareAction()
	{
		try 
		{
			$params = json_decode(stripslashes($_POST['DATA']));

			$post = array(
				'id'	=> $params->cloudId,
				'mode'	=> $params->mode,
				); 

			$row = $this->getPostTable()->fetchPost($post['id']); 

			if ($row)
			{
				// fb/twitter/g+/email share logic here
				
				$result = array(
					'status' => true,
					'msg' => "Success",
					'cloudId' => $postId,
					);
			}
			else
			{
				$result = array(
					'status' => false,
					'msg' => "Can Not Find The Post",
					);
			}
		} 
		catch (\Exception $e) 
		{
			$result = array(
				'status' => false,
				'msg' => "Exception: " .$e->getMessage(),
				);
		}

		return new JsonModel(array(
			'RESPONSE' => $result,
			));
	}

	public function approveAction()
	{

	}

	public function rejectAction()
	{

	}

	public function promoteAction()
	{

	}

}
