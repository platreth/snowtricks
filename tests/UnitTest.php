<?php
namespace App\Tests;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\User;
use App\Form\UserType;
use App\Model\TestObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\FormTypeInterface;


class UnitTest extends TestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->systemUnderTest = new CommentType();
    }

    /**
     * Tests that form is correctly build according to specs
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        $formBuilderMock->expects($this->atLeastOnce())->method('add')->withConsecutive(
            [$this->equalTo('content'), $this->equalTo(TextareaType::class)]);

        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->systemUnderTest->buildForm($formBuilderMock, []);
    }
}