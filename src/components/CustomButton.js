import { Text, TouchableOpacity, View, ActivityIndicator } from 'react-native';

const CustomButton = ({
  containerStyle,
  buttonStyle,
  textStyle,
  label,
  onPress,
  variant = 'primary',
  loading = false,
  disabled = false,
}) => {
  const isPrimary = variant === 'primary';
  const isOutline = variant === 'outline';

  const getButtonStyles = () => {
    const base = {
      alignItems: 'center',
      justifyContent: 'center',
      paddingVertical: 16,
      paddingHorizontal: 32,
      borderRadius: 25,
    };

    if (isPrimary) {
      return {
        ...base,
        backgroundColor: disabled ? '#B0C4DE' : '#6B8BB8',
      };
    }

    if (isOutline) {
      return {
        ...base,
        backgroundColor: 'transparent',
        borderWidth: 2,
        borderColor: '#6B8BB8',
      };
    }

    return base;
  };

  const getTextStyles = () => {
    const base = {
      fontSize: 16,
      fontWeight: '600',
    };

    if (isPrimary) {
      return { ...base, color: '#FFFFFF' };
    }

    if (isOutline) {
      return { ...base, color: '#6B8BB8' };
    }

    return base;
  };

  return (
    <View style={containerStyle}>
      <TouchableOpacity
        onPress={onPress}
        disabled={disabled || loading}
        activeOpacity={0.8}
      >
        <View style={[getButtonStyles(), buttonStyle]}>
          {loading ? (
            <ActivityIndicator color={isPrimary ? '#FFFFFF' : '#6B8BB8'} />
          ) : (
            <Text style={[getTextStyles(), textStyle]}>{label}</Text>
          )}
        </View>
      </TouchableOpacity>
    </View>
  );
};

export default CustomButton;
