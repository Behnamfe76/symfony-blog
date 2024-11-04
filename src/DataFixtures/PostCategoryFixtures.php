<?php

namespace App\DataFixtures;

use App\Entity\PostCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'سیاست',
                'description' => 'دسته سیاست در برگیرنده اخبار و تحلیل های روز دنیای سیایت میباشد',
            ],
            [
                'name' => 'هنر و فیلم',
                'description' => 'دسته هنر و فیلم در برگیرنده اخبار و نقد های هنر و فیلم میباشد',
            ],
            [
                'name' => 'فرهنگ و جامعه',
                'description' => 'دسته فرهنگ و جامعه در برگیرنده اخبار و تحلیل های روز دنیای فرهنگ و جامعه میباشد',
            ],
        ];

        foreach ($categories as $data) {
            $postCategory = new PostCategory();
            $postCategory->setName($data['name']);
            $postCategory->setDescription($data['description']);
            $manager->persist($postCategory);
        }

        $manager->flush();
    }
}
