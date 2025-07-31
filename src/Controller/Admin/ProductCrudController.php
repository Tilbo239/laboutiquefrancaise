<?php

namespace App\Controller\Admin;

use BcMath\Number;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            // ...
        ;
    }


    public function configureFields(string $pageName): iterable
    {

        $required = true;

        if($pageName == 'edit'){
            $required = false;
        }

        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Le nom du produit'),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->setLabel('URL')
                ->setHelp('Le slug est utilisé pour l\'URL du produit'),
            AssociationField::new('category')
                ->setLabel('Catégorie')
                ->setHelp('La catégorie à laquelle appartient le produit'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('La description du produit'),
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Le prix du produit sans le sigle € en euros'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '5.5%' => '5.5',
                '10%' => '10',
                '20%' => '20',
            ])->setHelp('Le taux de TVA applicable au produit'),
             ImageField::new('illustration')
                ->setLabel('Image')->setHelp('L\'image du produit 600x600px')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('uploads')
                ->setUploadDir('public/uploads')
                ->setRequired($required),


        ];
    }
}
