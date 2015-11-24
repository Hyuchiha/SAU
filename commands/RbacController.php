<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
class RbacController extends Controller
{
    public function actionCreateRoles()
    {
        $auth = Yii::$app->authManager;

        // Creación de roles
        $administrator = $auth->createRole('administrator');    // Administrador
        $executive = $auth->createRole('executive');            // Directivo
        $employeeArea = $auth->createRole('employeeArea');      // Empleado de área
        $responsibleArea = $auth->createRole('responsibleArea');// Responsable de área

        // Guardado de roles en la base de datos
        $auth->add($administrator);
        $auth->add($executive);
        $auth->add($employeeArea);
        $auth->add($responsibleArea);
    }

    public function actionAsignRoles()
    {
        /*
        $auth = Yii::$app->authManager;

        // Obtención de los roles
        $administrator = $auth->getRole('administrator');
        $executive = $auth->getRole('executive');
        $employeeArea = $auth->getRole('employeeArea');
        $responsibleArea = $auth->getRole('responsibleArea');
        $requester = $auth->getRole('requester');

        // Asignación de roles
        $auth->assign($administrator, #);
        $auth->assign($executive, #);
        $auth->assign($employeeArea, #);
        $auth->assign($responsibleArea, #);
        $auth->assign($requester, #);
        */
    }

    public function actionRules()
    {
        $auth = Yii::$app->authManager;

        // add the rule
        $rule = new \app\rbac\MyAreaRule;
        $auth->add($rule);

        // add the "updateOwnPost" permission and associate the rule with it.
        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Actualizar categoria de mi area';
        $updateCategory->ruleName = $rule->name;
        $auth->add($updateCategory);
        $responsibleArea = $auth->getRole('responsibleArea');
        $auth->addChild($responsibleArea, $updateCategory);
    }
}
?>
