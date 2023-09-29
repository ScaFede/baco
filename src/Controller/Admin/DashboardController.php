<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\CompetenzeBis;
use App\Entity\Scambi;
use App\Entity\Categorie;
use App\Entity\Citta;


use App\Repository\UserRepository;
use App\Repository\CompetenzeBisRepository;
use App\Repository\ScambiRepository;
use App\Repository\CategorieRepository;
use App\Repository\CittaRepository;


class DashboardController extends AbstractDashboardController
{

  /**
 * @Route("/admin")
 */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response  {



        //return parent::index();
      //  return $this->render('competenze_bis/index.html.twig');
       return $this->render('admin/index.html.twig'); //Redirect in admin per autenticati

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

      //  $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
      //  return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. Make your dashboard redirect to different pages depending on the user
        /*if ('jane' === $this->getUser()->getUsername()) {
            return $this->redirect('...');
        }*/
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Baco')

            // you can include HTML contents too (e.g. to link to an image)
          // ->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

           // by default EasyAdmin displays a black square as its default favicon;
           // use this method to display a custom favicon: the given path is passed
           // "as is" to the Twig asset() function:
           // <link rel="shortcut icon" href="{{ asset('...') }}">
           ->setFaviconPath('favicon.svg')

           // the domain used by default is 'messages'
           ->setTranslationDomain('baco-domain')


           // set this option if you prefer the page content to span the entire
           // browser width, instead of the default design which sets a max width
          // ->renderContentMaximized()

           // set this option if you prefer the sidebar (which contains the main menu)
           // to be displayed as a narrow column instead of the default expanded design
           ->renderSidebarMinimized()

           // by default, users can select between a "light" and "dark" mode for the
           // backend interface. Call this method if you prefer to disable the "dark"
           // mode for any reason (e.g. if your interface customizations are not ready for it)
          // ->disableDarkMode()

           // by default, all backend URLs are generated as absolute URLs. If you
           // need to generate relative URLs instead, call this method
           ->generateRelativeUrls()

           // set this option if you want to enable locale switching in dashboard.
           // IMPORTANT: this feature won't work unless you add the {_locale}
           // parameter in the admin dashboard URL (e.g. '/admin/{_locale}').
           // the name of each locale will be rendered in that locale
           // (in the following example you'll see: "English", "Polski")
           ->setLocales(['it', 'en']);
           // to customize the labels of locales, pass a key => value array
           // (e.g. to display flags; although it's not a recommended practice,
           // because many languages/locales are not associated to a single country)
          /* ->setLocales([
               'it' => 'italiano',
               'en' => 'inglese'
           ])*/
           // to further customize the locale option, pass an instance of
           // EasyCorp\Bundle\EasyAdminBundle\Config\Locale
          /* ->setLocales([
               'it', // locale without custom options
               Locale::new('en', 'english', 'far fa-language') // custom label and icon
           ]);*/
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('CompetenzeBis', 'fa fa-list', CompetenzeBis::class);
        yield MenuItem::linkToCrud('Scambi', 'fa fa-solid fa-shuffle', Scambi::class);
        yield MenuItem::linkToCrud('Categorie', 'fa fa-solid fa-tag', Categorie::class);
        yield MenuItem::linkToCrud('Città', 'fa fa-solid fa-building', Citta::class);


        return [
          MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

        /*   MenuItem::section('Blog'),
           MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
           MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class),

           MenuItem::section('Users'),
           MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
           MenuItem::linkToCrud('Users', 'fa fa-user', User::class),*/


           // MenuItem::section('CompetenzeBis'),
           // MenuItem::linkToCrud('CompetenzeBis', 'fa fa-comment', CompetenzeBis::class),
       ];
    }
}
