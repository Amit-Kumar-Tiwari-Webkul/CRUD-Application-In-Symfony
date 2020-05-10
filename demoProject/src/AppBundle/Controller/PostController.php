<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class PostController extends Controller
{
    /**
     * @Route("/viewPost/{id}", name="view_post_route")
     */
    public function viewPostsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);
        return $this->render('pages/view.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/createPost", name="create_post_route")
     */
    public function createPostsAction(Request $request)
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
        ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
        ->add('category', textType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', submitType::class, array('label' => 'Create Post', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:10px;')))
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $description = $form['description']->getData();
            $category = $form['category']->getData();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setCategory($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('message', 'Post Created Successfuly');
            return $this->redirectToRoute('view_all_posts_route');
        }
        return $this->render('pages/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/editPost/{id}", name="edit_post_route")
     */
    public function editPostsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);
        $post->setTitle($post->getTitle());
        $post->setDescription($post->getDescription());
        $post->setCategory($post->getCategory());
        $form = $this->createFormBuilder($post)
        ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
        ->add('category', textType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', submitType::class, array('label' => 'Update Post', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:10px;')))
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $description = $form['description']->getData();
            $category = $form['category']->getData();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setCategory($category);
            $em->getRepository('AppBundle:Post')->find($id);
            $em->flush();
            $this->addFlash('message', 'Post Updated Successfuly');
            return $this->redirectToRoute('view_all_posts_route');
        }
        return $this->render('pages/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/deletePost/{id}", name="delete_post_route")
     */
    public function deletePostsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);
        $em->remove($post);
        $em->flush();
        $this->addFlash('message', 'Post Deleted Successfuly');
        return $this->redirectToRoute('view_all_posts_route');
    }

    /**
     * @Route("/", name="view_all_posts_route")
     */
    public function ViewAllPostsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findAll();
        return $this->render('pages/index.html.twig', ['posts' => $posts]);
    }
}
